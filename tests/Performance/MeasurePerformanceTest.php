<?php

namespace Okapi\Aop\Tests\Performance;

use Okapi\Aop\Tests\Performance\Kernel\MeasurePerformanceKernel;
use Okapi\Aop\Tests\Performance\Target\Numbers;
use Okapi\Aop\Tests\Util;
use Okapi\Filesystem\Filesystem;
use PHPUnit\Framework\Attributes\{DataProvider, RunTestsInSeparateProcesses, Test};
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Helper\{Table, TableStyle};
use Symfony\Component\Console\Output\ConsoleOutput;

#[RunTestsInSeparateProcesses]
class MeasurePerformanceTest extends TestCase
{
    private bool $useAspects;
    private bool $cached;

    private CONST MEASURE_TYPE_WITHOUT_ASPECTS     = 'Without Aspects';
    private CONST MEASURE_TYPE_WITH_ASPECTS        = 'With Aspects';
    private CONST MEASURE_TYPE_WITH_CACHED_ASPECTS = 'With Cached Aspects';

    private array $measures = [
        self::MEASURE_TYPE_WITHOUT_ASPECTS     => [],
        self::MEASURE_TYPE_WITH_ASPECTS        => [],
        self::MEASURE_TYPE_WITH_CACHED_ASPECTS => [],
    ];

    private const MEASURE_TYPE_FROM_START_TO_END = 'From Start to End';
    private const MEASURE_TYPE_BOOT              = 'Boot Time (Kernel::init())';
    private const MEASURE_TYPE_CLASS_LOADING     = 'Class Loading Time';
    private const MEASURE_TYPE_METHOD_CALL       = 'Method Call Time';

    private const START_TIME   = 'Start Time';
    private const END_TIME     = 'End Time';
    private const START_MEMORY = 'Start Memory';
    private const END_MEMORY   = 'End Memory';

    private const METRIC_TYPE_TIME   = 'Time';
    private const METRIC_TYPE_MEMORY = 'Memory';

    public static array $aspectCountAndTimesCount = [
        [1, 1],
        [5, 5],
        [20, 20],
        [50, 50],
        [100, 100],
        [500, 100],
        [100, 500],
        [1000, 500],
        [500, 1000],
        [1000, 1000],
        [1, 5000],
        [5000, 1],
        [5000, 1000],
        [1000, 5000],
        [5000, 5000],
    ];

    /**
     * @return array Count of array should be:
     *   <code>count($aspectCountAndTimesCount) * count($flags) + 1</code>
     *   <code>E.g. 13 * 3 + 1 = 40</code>
     */
    public static function dataProvider(): array
    {
        $flags = [
            self::MEASURE_TYPE_WITHOUT_ASPECTS     => ['useAspects' => false, 'cached' => false],
            self::MEASURE_TYPE_WITH_ASPECTS        => ['useAspects' => true,  'cached' => false],
            self::MEASURE_TYPE_WITH_CACHED_ASPECTS => ['useAspects' => true,  'cached' => true],
        ];

        $data = [];

        foreach (self::$aspectCountAndTimesCount as $count) {
            foreach ($flags as $measureType => $flag) {
                $aspectCount = $count[0];
                $timesCount = $count[1];

                $aspectsLabel = $aspectCount === 1 ? 'aspect' : 'aspects';
                $timesLabel = $timesCount === 1 ? 'time' : 'times';
                $dataProviderLabel = "$measureType: $aspectCount $aspectsLabel, $timesCount $timesLabel";

                $data[$dataProviderLabel] = [
                    'aspectCount' => $aspectCount,
                    'timesCount' => $timesCount,
                    'useAspects' => $flag['useAspects'],
                    'cached' => $flag['cached'],
                ];
            }
        }

        // Cleanup data
        $data['Cleanup'] = [
            'aspectCount' => 0,
            'timesCount' => 0,
            'useAspects' => false,
            'cached' => false,
        ];

        return $data;
    }

    /** @noinspection PhpUnusedLocalVariableInspection */
    #[Test]
    #[DataProvider('dataProvider')]
    public function measurePerformance(
        int $aspectCount,
        int $timesCount,
        bool $useAspects,
        bool $cached
    ): void {
        $firstRun = $aspectCount === self::$aspectCountAndTimesCount[0][0]
            && $timesCount === self::$aspectCountAndTimesCount[0][1]
            && !$useAspects
            && !$cached;
        $shouldCleanCache = $firstRun || ($useAspects && !$cached);
        $lastRun = $aspectCount === 0 && $timesCount === 0 && !$useAspects && !$cached;

        if ($firstRun) {
            $this->cleanup();
        }

        if ($shouldCleanCache) {
            Util::clearCache();
        }

        if ($lastRun) {
            $this->cleanup();
            $this->assertTrue(true);
            return;
        }

        /**
         * @var class-string<MeasurePerformanceKernel> $kernel
         * @var class-string<Numbers>[] $classes
         */
        [$kernel, $classes] = $this->createKernelAspectsAndClasses($useAspects, $aspectCount);

        $this->useAspects = $useAspects;
        $this->cached = $cached;

        $this->startMeasure(self::MEASURE_TYPE_FROM_START_TO_END);
        $this->startMeasure(self::MEASURE_TYPE_BOOT);

        if ($useAspects) {
            /** @var MeasurePerformanceKernel $kernel */
            $kernel::init();
        }

        $this->  endMeasure(self::MEASURE_TYPE_BOOT);
        $this->startMeasure(self::MEASURE_TYPE_CLASS_LOADING);

        /** @var Numbers[] $numberClasses */
        $numberClasses = [];
        foreach ($classes as $class) {
            $numberClasses[] = new $class();
        }

        $this->endMeasure(self::MEASURE_TYPE_CLASS_LOADING);
        // This is only used for validating the results, comment it out
        // $expectedResults = [];
        // $actualResults = [];
        $this->startMeasure(self::MEASURE_TYPE_METHOD_CALL);

        // There are 2 loops here, that could be merged into one, but the
        // performance difference is negligible, so it's not worth the
        // readability loss
        if ($useAspects) {
            // There is only one numbers class when using aspects
            /** @var Numbers $numbers */
            $numbers = $numberClasses[0];

            foreach (range(1, $timesCount) as $ignored) {
                $result = $numbers->get();

                // This is only used for validating the results, comment it out
                // $expectedResults[] = $aspectCount;
                // $actualResults[] = $result;
            }
        } else {
            foreach (range(1, $aspectCount) as $i) {
                /** @var Numbers $numbers */
                $numbers = $numberClasses[$i - 1];

                foreach (range(1, $timesCount) as $ignored) {
                    // Here we emulate the aspect by adding 1 to the result
                    $numbers->add(1);
                }

                $result = $numbers->get();

                // This is only used for validating the results, comment it out
                // $expectedResults[] = $timesCount;
                // $actualResults[] = $result;
            }
        }

        $this->endMeasure(self::MEASURE_TYPE_METHOD_CALL);
        $this->endMeasure(self::MEASURE_TYPE_FROM_START_TO_END);

        // This is only used for validating the results, comment it out
        // $this->assertEquals($expectedResults, $actualResults);

        $this->saveMeasuresToFile();

        if ($useAspects && $cached) {
            $this->printMeasures($this->dataName());
        }

        $this->assertTrue(true);
    }

    /**
     * @param bool $useAspects
     * @param int  $aspectCount
     *
     * @return array{class-string<MeasurePerformanceKernel>|null, class-string<Numbers>[]}
     */
    private function createKernelAspectsAndClasses(
        bool $useAspects,
        int $aspectCount
    ): array {
        $tempDirectory = __DIR__ . '/Temp';
        if (!file_exists($tempDirectory)) {
            Filesystem::mkdir($tempDirectory);
        }

        if ($useAspects) {
            $newKernelFilePath      = "$tempDirectory/MeasurePerformanceKernel$aspectCount.php";
            $newKernelFileNamespace = "\\Okapi\\Aop\\Tests\\Performance\\Temp\\MeasurePerformanceKernel$aspectCount";
            if (file_exists($newKernelFilePath)) {
                return [$newKernelFileNamespace, [Numbers::class]];
            }

            $kernelFile = Filesystem::readFile(__DIR__ . '/Kernel/MeasurePerformanceKernel.php');

            $addOneAspectLineNumber = 0;
            $addOneAspectLine       = '';
            $lines                  = explode("\n", $kernelFile);
            foreach ($lines as $lineNumber => &$line) {
                // Replace namespace
                if (str_contains($line, 'namespace Okapi\\Aop\\Tests\\Performance\\Kernel')) {
                    $line = str_replace(
                        search: 'Kernel',
                        replace: 'Temp',
                        subject: $line,
                    );
                }

                // Replace class name
                if (str_contains($line, 'class MeasurePerformanceKernel')) {
                    $line = str_replace(
                        search: 'MeasurePerformanceKernel',
                        replace: "MeasurePerformanceKernel$aspectCount",
                        subject: $line,
                    );
                }

                // Find the line where the "AddOneAspect" is added to the kernel
                if (str_contains($line, 'AddOneAspect::class,')) {
                    $addOneAspectLineNumber = $lineNumber;
                    $addOneAspectLine       = $line;
                    break;
                }
            }

            $aspects = [];
            foreach (range(1, $aspectCount) as $aspectNumber) {
                $aspects[] = str_replace(
                    search: 'Aspect\\AddOneAspect::class,',
                    replace: "Temp\\AddOneAspect$aspectNumber::class,",
                    subject: $addOneAspectLine,
                );

                // Read aspect file
                static $aspectFile;
                if (!$aspectFile) {
                    $aspectFile = Filesystem::readFile(__DIR__ . '/Aspect/AddOneAspect.php');
                }

                $newAspectFilePath = __DIR__ . "/Temp/AddOneAspect$aspectNumber.php";
                if (file_exists($newAspectFilePath)) {
                    continue;
                }

                $newAspectFile = $aspectFile;

                // Replace namespace
                $newAspectFile = str_replace(
                    search: 'namespace Okapi\\Aop\\Tests\\Performance\\Aspect;',
                    replace: 'namespace Okapi\\Aop\\Tests\\Performance\\Temp;',
                    subject: $newAspectFile,
                );

                // Replace class name
                $newAspectFile = str_replace(
                    search: 'class AddOneAspect',
                    replace: "class AddOneAspect$aspectNumber",
                    subject: $newAspectFile,
                );

                // Write aspect file
                Filesystem::writeFile(
                    $newAspectFilePath,
                    $newAspectFile,
                );
            }

            unset($lines[$addOneAspectLineNumber]);

            array_splice($lines, $addOneAspectLineNumber, 0, $aspects);

            $kernelFile = implode("\n", $lines);

            // Create Temp directory
            if (!file_exists(__DIR__ . '/Temp')) {
                Filesystem::mkdir(__DIR__ . '/Temp');
            }

            // Write kernel file
            Filesystem::writeFile(
                $newKernelFilePath,
                $kernelFile,
            );

            return [$newKernelFileNamespace, [Numbers::class]];
        } else {
            $classes = [];
            foreach (range(1, $aspectCount) as $aspectNumber) {
                $classes[] = str_replace(
                    search: 'Target\\Numbers',
                    replace: "Temp\\Numbers$aspectNumber",
                    subject: Numbers::class,
                );

                // Read class file
                static $classFile;
                if (!$classFile) {
                    $classFile = Filesystem::readFile(__DIR__ . '/Target/Numbers.php');
                }

                $newClassFilePath = __DIR__ . "/Temp/Numbers$aspectNumber.php";
                if (file_exists($newClassFilePath)) {
                    continue;
                }

                $newClassFile = $classFile;

                // Replace namespace
                $newClassFile = str_replace(
                    search: 'namespace Okapi\\Aop\\Tests\\Performance\\Target;',
                    replace: 'namespace Okapi\\Aop\\Tests\\Performance\\Temp;',
                    subject: $newClassFile,
                );

                // Replace class name
                $newClassFile = str_replace(
                    search: 'class Numbers',
                    replace: "class Numbers$aspectNumber",
                    subject: $newClassFile,
                );

                // Write class file
                Filesystem::writeFile(
                    $newClassFilePath,
                    $newClassFile,
                );
            }

            return [null, $classes];
        }
    }

    private function startMeasure(string $name): void
    {
        $type = $this->getMeasureType();

        $this->measures[$type][$name] = [
            self::START_TIME => microtime(true),
            self::START_MEMORY => memory_get_usage(),
        ];
    }

    private function endMeasure(string $name): void
    {
        $type = $this->getMeasureType();

        $this->measures[$type][$name][self::END_TIME] = microtime(true);
        $this->measures[$type][$name][self::END_MEMORY] = memory_get_usage();
    }

    private function saveMeasuresToFile(): void
    {
        $tempDirectory = __DIR__ . '/Temp';

        $measuresFile = "$tempDirectory/measures.json";
        if (!file_exists($measuresFile)) {
            $measures = $this->measures;
        } else {
            $measures = json_decode(Filesystem::readFile($measuresFile), true);
            $measures[$this->getMeasureType()] = $this->measures[$this->getMeasureType()];
        }

        // Save it every execution, because #[RunTestsInSeparateProcesses]
        // will not store $this->measures between executions
        Filesystem::writeFile(
            $measuresFile,
            json_encode($measures, JSON_PRETTY_PRINT)
        );
    }

    private function getMeasureType(): string
    {
        if ($this->useAspects) {
            if ($this->cached) {
                $type = self::MEASURE_TYPE_WITH_CACHED_ASPECTS;
            } else {
                $type = self::MEASURE_TYPE_WITH_ASPECTS;
            }
        } else {
            $type = self::MEASURE_TYPE_WITHOUT_ASPECTS;
        }

        return $type;
    }

    // region Print Measures

    private function printMeasures(string $dataProviderLabel): void
    {
        $this->measures = json_decode(
            json: Filesystem::readFile(__DIR__ . '/Temp/measures.json'),
            associative: true,
        );

        $dataProviderLabel = str_replace(
            search: self::MEASURE_TYPE_WITH_CACHED_ASPECTS . ': ',
            replace: '',
            subject: $dataProviderLabel,
        );

        $output = new ConsoleOutput();

        // First Table
        $output->writeln("<info>Table 1: Without Aspects vs With Aspects ($dataProviderLabel)</info>");
        $this->printTable($output, self::MEASURE_TYPE_WITH_ASPECTS);

        // Second Table
        $output->writeln('');
        $output->writeln("<info>Table 2: Without Aspects vs With Cached Aspects ($dataProviderLabel)</info>");
        $this->printTable($output, self::MEASURE_TYPE_WITH_CACHED_ASPECTS);
        $output->writeln('');
        $output->writeln('');
        $output->writeln('');
    }

    private function printTable(ConsoleOutput $output, string $comparisonAspect): void
    {
        $table = new Table($output);
        $headers = [
            'Measure Type',
            'Metric',
            'Without Aspects',
            $comparisonAspect,
            'Difference',
        ];
        $table->setHeaders($headers);

        $measuresWithoutAspects = $this->measures[self::MEASURE_TYPE_WITHOUT_ASPECTS];
        foreach ($measuresWithoutAspects as $measureType => $metrics) {
            $table->addRow($this->generateRowData(
                $measureType,
                $comparisonAspect,
                self::METRIC_TYPE_TIME,
            ));
        }

        foreach ($measuresWithoutAspects as $measureType => $metrics) {
            $table->addRow($this->generateRowData(
                $measureType,
                $comparisonAspect,
                self::METRIC_TYPE_MEMORY,
            ));
        }

        // Pad the values to the left
        $tableStyle = new TableStyle();
        $tableStyle->setPadType(STR_PAD_LEFT);
        $table->setColumnStyle(2, $tableStyle);
        $table->setColumnStyle(3, $tableStyle);
        $table->setColumnStyle(4, $tableStyle);

        $table->render();
    }

    private function generateRowData(
        string $measureType,
        string $comparisonAspect,
        string $metricType
    ): array {
        // Get start and end metrics
        $startMetric = $metricType === self::METRIC_TYPE_TIME
            ? self::START_TIME
            : self::START_MEMORY;

        $endMetric = $metricType === self::METRIC_TYPE_TIME
            ? self::END_TIME
            : self::END_MEMORY;

        // Calculate without aspects
        $withoutAspectsValue = $this->measures[self::MEASURE_TYPE_WITHOUT_ASPECTS][$measureType][$endMetric]
            - $this->measures[self::MEASURE_TYPE_WITHOUT_ASPECTS][$measureType][$startMetric];

        // Calculate with aspects
        $comparisonValue = $this->measures[$comparisonAspect][$measureType][$endMetric]
            - $this->measures[$comparisonAspect][$measureType][$startMetric];

        // For memory, convert to MB
        if ($metricType === self::METRIC_TYPE_MEMORY) {
            $withoutAspectsValue /= (1024 * 1024);
            $comparisonValue /= (1024 * 1024);
        }

        // Calculate difference
        $difference = $comparisonValue - $withoutAspectsValue;

        // Time: 4 digits, Memory: 2 digits
        $digits = $metricType === self::METRIC_TYPE_TIME ? 4 : 2;

        // Format digits
        $withoutAspectsValue = number_format($withoutAspectsValue, $digits);
        $comparisonValue = number_format($comparisonValue, $digits);
        $difference = number_format($difference, $digits);

        // Prefix difference with + or -
        $prefix = $difference > 0 ? '+' : '';
        $difference = "$prefix$difference";

        // Append unit
        if ($metricType === self::METRIC_TYPE_TIME) {
            $append = ' s';
        } else {
            $append = ' MB';
        }
        $withoutAspectsValue .= $append;
        $comparisonValue .= $append;
        $difference .= $append;

        return [
            $measureType,
            $metricType,
            $withoutAspectsValue,
            $comparisonValue,
            $difference,
        ];
    }

    // endregion

    private function cleanup(): void
    {
        Filesystem::rm(__DIR__ . '/Temp', recursive: true, force: true);
    }
}
