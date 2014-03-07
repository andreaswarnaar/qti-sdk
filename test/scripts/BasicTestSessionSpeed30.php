<?php
use qtism\runtime\storage\common\AbstractStorage;
use qtism\runtime\tests\AssessmentTestSession;
use qtism\data\storage\xml\XmlCompactDocument;
use qtism\data\storage\xml\XmlDocument;
use qtism\common\datatypes\Identifier;
use qtism\common\enums\BaseType;
use qtism\common\enums\Cardinality;
use qtism\runtime\common\ResponseVariable;
use qtism\runtime\common\State;
use qtism\data\AssessmentTest;
use qtism\runtime\tests\AssessmentTestSessionFactory;
use qtism\runtime\storage\binary\TemporaryQtiBinaryStorage;
use qtism\data\storage\php\PhpDocument;

require_once(dirname(__FILE__) . '/../../qtism/qtism.php');

function loadTestDefinition(array &$average = null) {
    $start = microtime();
    
    $phpDoc = new PhpDocument();
    $phpDoc->load(dirname(__FILE__) . '/../../test/samples/custom/php/linear_30_items.php');
    
    if (is_null($average) === false) {
        spentTime($start, microtime(), $average);
    }
    
    return $phpDoc->getDocumentComponent();
}

function createFactory(AssessmentTest $assessmentTest) {
    return new AssessmentTestSessionFactory($assessmentTest);
}

function createStorage(AssessmentTestSessionFactory $factory) {
    return new TemporaryQtiBinaryStorage($factory);
}

function spentTime($start, $end, array &$registration = null) {
    $startTime = explode(' ', $start);
    $endTime = explode(' ', $end);
    $time = ($endTime[0] + $endTime[1]) - ($startTime[0] + $startTime[1]);
    
    if (!is_null($registration)) {
        $registration[] = $time;
    }
    
    return $time;
}

function attempt(AssessmentTestSession $session, $identifier, array &$average = null) {
    $start = microtime();

    $session->beginAttempt();
    $session->endAttempt(new State(array(new ResponseVariable('RESPONSE', Cardinality::SINGLE, BaseType::IDENTIFIER, new Identifier($identifier)))));

    if (is_null($average) === false) {
        spentTime($start, microtime(), $average);
    }
}

function retrieve(AbstractStorage $storage, $sessionId, array &$average = null) {
    $start = microtime();

    $session = $storage->retrieve($sessionId);

    if (is_null($average) === false) {
        spentTime($start, microtime(), $average);
    }

    return $session;
}

function persist(AbstractStorage $storage, AssessmentTestSession $session, &$average = null) {
    $start = microtime();

    $storage->persist($session);

    if (is_null($average) === false) {
        spentTime($start, microtime(), $average);
    }
}

function moveNext(AssessmentTestSession $session, array &$average) {
    $start = microtime();

    $session->moveNext();

    if (is_null($average) === false) {
        spentTime($start, microtime(), $average);
    }
}

$averageAttempt = array();
$effectiveAverageAttempt = array();
$averageRetrieve = array();
$averagePersist = array();
$averageNext = array();
$averageLoad = array();

// Beginning of the session + persistance.
$start = microtime();

$storage = createStorage(createFactory(loadTestDefinition($averageLoad)));
$session = $storage->instantiate();
$sessionId = $session->getSessionId();
$session->beginTestSession();
$storage->persist($session);
unset($session);
unset($storage);

$end = microtime();
echo "Beginning of the session + persistance (" . spentTime($start, $end) . ")\n";

// Retrieving session + make an attempt + persistance.
$start = microtime();

$storage = createStorage(createFactory(loadTestDefinition($averageLoad)));
$session = retrieve($storage, $sessionId, $averageRetrieve);
attempt($session, 'ChoiceA', $effectiveAverageAttempt);
moveNext($session, $averageNext);
persist($storage, $session, $averagePersist);
unset($session);
unset($storage);

$end = microtime();
echo "Retrieving session + attempt 1 + persistance (" . spentTime($start, $end, $averageAttempt) . ")\n";

// Retrieving session + make an attempt + persistance.
$start = microtime();

$storage = createStorage(createFactory(loadTestDefinition($averageLoad)));
$session = retrieve($storage, $sessionId, $averageRetrieve);
attempt($session, 'ChoiceB', $effectiveAverageAttempt);
moveNext($session, $averageNext);
persist($storage, $session, $averagePersist);
unset($session);
unset($storage);

$end = microtime();
echo "Retrieving session + attempt 2 + persistance (" . spentTime($start, $end, $averageAttempt) . ")\n";

// Retrieving session + make an attempt + persistance.
$start = microtime();

$storage = createStorage(createFactory(loadTestDefinition($averageLoad)));
$session = retrieve($storage, $sessionId, $averageRetrieve);
attempt($session, 'ChoiceC', $effectiveAverageAttempt);
moveNext($session, $averageNext);
persist($storage, $session, $averagePersist);
unset($session);
unset($storage);

$end = microtime();
echo "Retrieving session + attempt 3 + persistance (" . spentTime($start, $end, $averageAttempt) . ")\n";

// Retrieving session + make an attempt + persistance.
$start = microtime();

$storage = createStorage(createFactory(loadTestDefinition($averageLoad)));
$session = retrieve($storage, $sessionId, $averageRetrieve);
attempt($session, 'ChoiceD', $effectiveAverageAttempt);
moveNext($session, $averageNext);
persist($storage, $session, $averagePersist);
unset($session);
unset($storage);

$end = microtime();
echo "Retrieving session + attempt 4 + persistance (" . spentTime($start, $end, $averageAttempt) . ")\n";

// Retrieving session + make an attempt + persistance.
$start = microtime();

$storage = createStorage(createFactory(loadTestDefinition($averageLoad)));
$session = retrieve($storage, $sessionId, $averageRetrieve);
attempt($session, 'ChoiceE', $effectiveAverageAttempt);
moveNext($session, $averageNext);
persist($storage, $session, $averagePersist);
unset($session);
unset($storage);

$end = microtime();
echo "Retrieving session + attempt 5 + persistance (" . spentTime($start, $end, $averageAttempt) . ")\n";

// Retrieving session + make an attempt + persistance.
$start = microtime();

$storage = createStorage(createFactory(loadTestDefinition($averageLoad)));
$session = retrieve($storage, $sessionId, $averageRetrieve);
attempt($session, 'ChoiceF', $effectiveAverageAttempt);
moveNext($session, $averageNext);
persist($storage, $session, $averagePersist);
unset($session);
unset($storage);

$end = microtime();
echo "Retrieving session + attempt 6 + persistance (" . spentTime($start, $end, $averageAttempt) . ")\n";

// Retrieving session + make an attempt + persistance.
$start = microtime();

$storage = createStorage(createFactory(loadTestDefinition($averageLoad)));
$session = retrieve($storage, $sessionId, $averageRetrieve);
attempt($session, 'ChoiceG', $effectiveAverageAttempt);
moveNext($session, $averageNext);
persist($storage, $session, $averagePersist);
unset($session);
unset($storage);

$end = microtime();
echo "Retrieving session + attempt 7 + persistance (" . spentTime($start, $end, $averageAttempt) . ")\n";

// Retrieving session + make an attempt + persistance.
$start = microtime();

$storage = createStorage(createFactory(loadTestDefinition($averageLoad)));
$session = retrieve($storage, $sessionId, $averageRetrieve);
attempt($session, 'ChoiceH', $effectiveAverageAttempt);
moveNext($session, $averageNext);
persist($storage, $session, $averagePersist);
unset($session);
unset($storage);

$end = microtime();
echo "Retrieving session + attempt 8 + persistance (" . spentTime($start, $end, $averageAttempt) . ")\n";

// Retrieving session + make an attempt + persistance.
$start = microtime();

$storage = createStorage(createFactory(loadTestDefinition($averageLoad)));
$session = retrieve($storage, $sessionId, $averageRetrieve);
attempt($session, 'ChoiceI', $effectiveAverageAttempt);
moveNext($session, $averageNext);
persist($storage, $session, $averagePersist);
unset($session);
unset($storage);

$end = microtime();
echo "Retrieving session + attempt 9 + persistance (" . spentTime($start, $end, $averageAttempt) . ")\n";

// Retrieving session + make an attempt + persistance.
$start = microtime();

$storage = createStorage(createFactory(loadTestDefinition($averageLoad)));
$session = retrieve($storage, $sessionId, $averageRetrieve);
attempt($session, 'ChoiceJ', $effectiveAverageAttempt);
moveNext($session, $averageNext);
persist($storage, $session, $averagePersist);
unset($session);
unset($storage);

$end = microtime();
echo "Retrieving session + attempt 10 + persistance (" . spentTime($start, $end, $averageAttempt) . ")\n";

// Retrieving session + make an attempt + persistance.
$start = microtime();

$storage = createStorage(createFactory(loadTestDefinition($averageLoad)));
$session = retrieve($storage, $sessionId, $averageRetrieve);
attempt($session, 'ChoiceK', $effectiveAverageAttempt);
moveNext($session, $averageNext);
persist($storage, $session, $averagePersist);
unset($session);
unset($storage);

$end = microtime();
echo "Retrieving session + attempt 11 + persistance (" . spentTime($start, $end, $averageAttempt) . ")\n";

// Retrieving session + make an attempt + persistance.
$start = microtime();

$storage = createStorage(createFactory(loadTestDefinition($averageLoad)));
$session = retrieve($storage, $sessionId, $averageRetrieve);
attempt($session, 'ChoiceL', $effectiveAverageAttempt);
moveNext($session, $averageNext);
persist($storage, $session, $averagePersist);
unset($session);
unset($storage);

$end = microtime();
echo "Retrieving session + attempt 12 + persistance (" . spentTime($start, $end, $averageAttempt) . ")\n";

// Retrieving session + make an attempt + persistance.
$start = microtime();

$storage = createStorage(createFactory(loadTestDefinition($averageLoad)));
$session = retrieve($storage, $sessionId, $averageRetrieve);
attempt($session, 'ChoiceM', $effectiveAverageAttempt);
moveNext($session, $averageNext);
persist($storage, $session, $averagePersist);
unset($session);
unset($storage);

$end = microtime();
echo "Retrieving session + attempt 13 + persistance (" . spentTime($start, $end, $averageAttempt) . ")\n";

// Retrieving session + make an attempt + persistance.
$start = microtime();

$storage = createStorage(createFactory(loadTestDefinition($averageLoad)));
$session = retrieve($storage, $sessionId, $averageRetrieve);
attempt($session, 'ChoiceN', $effectiveAverageAttempt);
moveNext($session, $averageNext);
persist($storage, $session, $averagePersist);
unset($session);
unset($storage);

$end = microtime();
echo "Retrieving session + attempt 14 + persistance (" . spentTime($start, $end, $averageAttempt) . ")\n";

// Retrieving session + make an attempt + persistance.
$start = microtime();

$storage = createStorage(createFactory(loadTestDefinition($averageLoad)));
$session = retrieve($storage, $sessionId, $averageRetrieve);
attempt($session, 'ChoiceO', $effectiveAverageAttempt);
moveNext($session, $averageNext);
persist($storage, $session, $averagePersist);
unset($session);
unset($storage);

$end = microtime();
echo "Retrieving session + attempt 15 + persistance (" . spentTime($start, $end, $averageAttempt) . ")\n";

// Retrieving session + make an attempt + persistance.
$start = microtime();

$storage = createStorage(createFactory(loadTestDefinition($averageLoad)));
$session = retrieve($storage, $sessionId, $averageRetrieve);
attempt($session, 'ChoiceP', $effectiveAverageAttempt);
moveNext($session, $averageNext);
persist($storage, $session, $averagePersist);
unset($session);
unset($storage);

$end = microtime();
echo "Retrieving session + attempt 16 + persistance (" . spentTime($start, $end, $averageAttempt) . ")\n";

// Retrieving session + make an attempt + persistance.
$start = microtime();

$storage = createStorage(createFactory(loadTestDefinition($averageLoad)));
$session = retrieve($storage, $sessionId, $averageRetrieve);
attempt($session, 'ChoiceQ', $effectiveAverageAttempt);
moveNext($session, $averageNext);
persist($storage, $session, $averagePersist);
unset($session);
unset($storage);

$end = microtime();
echo "Retrieving session + attempt 17 + persistance (" . spentTime($start, $end, $averageAttempt) . ")\n";

// Retrieving session + make an attempt + persistance.
$start = microtime();

$storage = createStorage(createFactory(loadTestDefinition($averageLoad)));
$session = retrieve($storage, $sessionId, $averageRetrieve);
attempt($session, 'ChoiceR', $effectiveAverageAttempt);
moveNext($session, $averageNext);
persist($storage, $session, $averagePersist);
unset($session);
unset($storage);

$end = microtime();
echo "Retrieving session + attempt 18 + persistance (" . spentTime($start, $end, $averageAttempt) . ")\n";

// Retrieving session + make an attempt + persistance.
$start = microtime();

$storage = createStorage(createFactory(loadTestDefinition($averageLoad)));
$session = retrieve($storage, $sessionId, $averageRetrieve);
attempt($session, 'ChoiceS', $effectiveAverageAttempt);
moveNext($session, $averageNext);
persist($storage, $session, $averagePersist);
unset($session);
unset($storage);

$end = microtime();
echo "Retrieving session + attempt 19 + persistance (" . spentTime($start, $end, $averageAttempt) . ")\n";

// Retrieving session + make an attempt + persistance.
$start = microtime();

$storage = createStorage(createFactory(loadTestDefinition($averageLoad)));
$session = retrieve($storage, $sessionId, $averageRetrieve);
attempt($session, 'ChoiceT', $effectiveAverageAttempt);
moveNext($session, $averageNext);
persist($storage, $session, $averagePersist);
unset($session);
unset($storage);

$end = microtime();
echo "Retrieving session + attempt 20 + persistance (" . spentTime($start, $end, $averageAttempt) . ")\n";

// Retrieving session + make an attempt + persistance.
$start = microtime();

$storage = createStorage(createFactory(loadTestDefinition($averageLoad)));
$session = retrieve($storage, $sessionId, $averageRetrieve);
attempt($session, 'ChoiceU', $effectiveAverageAttempt);
moveNext($session, $averageNext);
persist($storage, $session, $averagePersist);
unset($session);
unset($storage);

$end = microtime();
echo "Retrieving session + attempt 21 + persistance (" . spentTime($start, $end, $averageAttempt) . ")\n";

// Retrieving session + make an attempt + persistance.
$start = microtime();

$storage = createStorage(createFactory(loadTestDefinition($averageLoad)));
$session = retrieve($storage, $sessionId, $averageRetrieve);
attempt($session, 'ChoiceV', $effectiveAverageAttempt);
moveNext($session, $averageNext);
persist($storage, $session, $averagePersist);
unset($session);
unset($storage);

$end = microtime();
echo "Retrieving session + attempt 22 + persistance (" . spentTime($start, $end, $averageAttempt) . ")\n";

// Retrieving session + make an attempt + persistance.
$start = microtime();

$storage = createStorage(createFactory(loadTestDefinition($averageLoad)));
$session = retrieve($storage, $sessionId, $averageRetrieve);
attempt($session, 'ChoiceW', $effectiveAverageAttempt);
moveNext($session, $averageNext);
persist($storage, $session, $averagePersist);
unset($session);
unset($storage);

$end = microtime();
echo "Retrieving session + attempt 23 + persistance (" . spentTime($start, $end, $averageAttempt) . ")\n";

// Retrieving session + make an attempt + persistance.
$start = microtime();

$storage = createStorage(createFactory(loadTestDefinition($averageLoad)));
$session = retrieve($storage, $sessionId, $averageRetrieve);
attempt($session, 'ChoiceX', $effectiveAverageAttempt);
moveNext($session, $averageNext);
persist($storage, $session, $averagePersist);
unset($session);
unset($storage);

$end = microtime();
echo "Retrieving session + attempt 24 + persistance (" . spentTime($start, $end, $averageAttempt) . ")\n";

// Retrieving session + make an attempt + persistance.
$start = microtime();

$storage = createStorage(createFactory(loadTestDefinition($averageLoad)));
$session = retrieve($storage, $sessionId, $averageRetrieve);
attempt($session, 'ChoiceY', $effectiveAverageAttempt);
moveNext($session, $averageNext);
persist($storage, $session, $averagePersist);
unset($session);
unset($storage);

$end = microtime();
echo "Retrieving session + attempt 25 + persistance (" . spentTime($start, $end, $averageAttempt) . ")\n";

// Retrieving session + make an attempt + persistance.
$start = microtime();

$storage = createStorage(createFactory(loadTestDefinition($averageLoad)));
$session = retrieve($storage, $sessionId, $averageRetrieve);
attempt($session, 'ChoiceZ', $effectiveAverageAttempt);
moveNext($session, $averageNext);
persist($storage, $session, $averagePersist);
unset($session);
unset($storage);

$end = microtime();
echo "Retrieving session + attempt 26 + persistance (" . spentTime($start, $end, $averageAttempt) . ")\n";

// Retrieving session + make an attempt + persistance.
$start = microtime();

$storage = createStorage(createFactory(loadTestDefinition($averageLoad)));
$session = retrieve($storage, $sessionId, $averageRetrieve);
attempt($session, 'ChoiceA', $effectiveAverageAttempt);
moveNext($session, $averageNext);
persist($storage, $session, $averagePersist);
unset($session);
unset($storage);

$end = microtime();
echo "Retrieving session + attempt 27 + persistance (" . spentTime($start, $end, $averageAttempt) . ")\n";

// Retrieving session + make an attempt + persistance.
$start = microtime();

$storage = createStorage(createFactory(loadTestDefinition($averageLoad)));
$session = retrieve($storage, $sessionId, $averageRetrieve);
attempt($session, 'ChoiceB', $effectiveAverageAttempt);
moveNext($session, $averageNext);
persist($storage, $session, $averagePersist);
unset($session);
unset($storage);

$end = microtime();
echo "Retrieving session + attempt 28 + persistance (" . spentTime($start, $end, $averageAttempt) . ")\n";

// Retrieving session + make an attempt + persistance.
$start = microtime();

$storage = createStorage(createFactory(loadTestDefinition($averageLoad)));
$session = retrieve($storage, $sessionId, $averageRetrieve);
attempt($session, 'ChoiceC', $effectiveAverageAttempt);
moveNext($session, $averageNext);
persist($storage, $session, $averagePersist);
unset($session);
unset($storage);

$end = microtime();
echo "Retrieving session + attempt 29 + persistance (" . spentTime($start, $end, $averageAttempt) . ")\n";

// Retrieving session + make an attempt + persistance.
$start = microtime();

$storage = createStorage(createFactory(loadTestDefinition($averageLoad)));
$session = retrieve($storage, $sessionId, $averageRetrieve);
attempt($session, 'ChoiceD', $effectiveAverageAttempt);
moveNext($session, $averageNext);
persist($storage, $session, $averagePersist);
unset($session);
unset($storage);

$end = microtime();
echo "Retrieving session + attempt 30 + persistance (" . spentTime($start, $end, $averageAttempt) . ")\n\n";

echo "Average attempt time = " . (array_sum($averageAttempt) / count($averageAttempt)) . "\n";
echo "Effective average attempt time = " . (array_sum($effectiveAverageAttempt) / count($effectiveAverageAttempt)) . "\n";
echo "Retrieve average time = " . (array_sum($averageRetrieve) / count($averageRetrieve)) . "\n";
echo "Persist average time = " . (array_sum($averagePersist) / count($averagePersist)) . "\n";
echo "MoveNext average time = " . (array_sum($averageNext) / count($averageNext)) . "\n";
echo "Load average time = " . (array_sum($averageLoad) / count($averageLoad)) . "\n";