<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    private $apiContext;

    /**
     * @var Encoders
     **/
    private $encoders;

    /**
     * @var Normalizers
     **/
    private $normalizers;

    /**
     * @var Serializer
     **/
    private $serializer;
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->encoders = [new JsonEncoder()]; // If no need for XmlEncoder
        $this->normalizers = [new DateTimeNormalizer(), new ObjectNormalizer()];
        $this->serializer = new Serializer($this->normalizers, $this->encoders);
    }

    /** @BeforeScenario */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        $this->apiContext = $scope->getEnvironment()->getContext
        (\Imbo\BehatApiExtension\Context\ApiContext::class);
    }

    /** @BeforeScenario */
    public function cleanUpDataBase()
    {
     /*  $host = '127.0.0.1';
        $db = 'esprit';
        $port = 3306;
        $user = 'root';
        $pass = 'root';
        $charset= 'utf8mb4';
        $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
        $opt = [
            PDO::ATTR_ERRMODE     => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES =>false,
        ];
        $pdo = new PDO($dsn,$user,$pass,$opt);
        $pdo->query('TRUNCATE event_config');
        $pdo->query('TRUNCATE event');*/
    }

    /**
     * @Given there are Event with the following details:
     */
    public function thereAreEventWithTheFollowingDetails(TableNode $table)
    {
        foreach ($table->getColumnsHash() as $event) {

            $this->apiContext->setRequestBody(
               $this->serializer($event)
            );

            $this->apiContext->requestPath(
                'postEvent',
                'POST'
            );
        }
    }

    protected function serializer($data)
    {
        return $this->serializer->serialize($data, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);
    }


}
