<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->bearerToken = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIzIiwianRpIjoiZjY5OTEzNGZiYWM0ZjUyMWJhZGVmMjU2NTE1MjRiYmRiNGNmZWU5ZmZhZGY4ZmNmOTQ5MDVmNTgwMzc5OGViODdjZDYwMjVmNzY5MTAwMjQiLCJpYXQiOjE1NzI5OTA0MjksIm5iZiI6MTU3Mjk5MDQyOSwiZXhwIjoxNjA0NjEyODI4LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.b7RS8yI3JJjP24DJmgX2FSDFWeaYufygmh_Pm6qsoKJpGOs6fOLVegMvinHgZ7T5KKscfRoXmGfb8ttKeX13lfANL51h1YTFw9z_cZatkRYF8BWJAEQyLRI-1tWmqMQY3LKMmVPqfqSJFosK0Q-Ac9SlXMMJOrw0umEbVfGPxwqnsXfCUIFQXFIHHIkBkoc94Ux_5TREdNu_KUw2XYRHGHMyU9yFK37YtBDKoqAiz-MVdW3eBkosLeTpqYCVrytT6XQhnomQSPfzNmQeWNOeRQ6uyLp-8hjW6er7N-00KGPFIaiRgpMuUex4EbCfhgrnUxK53EWMTpxELp7euCCrJHtFh_3u-EFnDBqybbl7tmauZDYGHFjV2SPh5pJQDGtjsDwJxvYTDM_mE5f_kqDSUAlJM7NVjt99EHBtx_dz1OC4CyXYgPVnyBc-VKqpS39OTUqbl06CydNuzrwyKLxRz-_GiWTWjk4JQp80IQx7jAcfC7aP-h4Ary9HfCAyrEZ_-Kx_OOZenmGSxoBpukDJ8yK47TQI9_z23E4GxFQQvsvoe8Gi76WoVsBZDH0hoiCHlgvt0OJFeCtqlSoGFKBVlzQgflW9u6Y8b5TSt2hL_tLeRe74qybeUTo3ef5ZlABTqA3y4MsKtk5xyiVOckssn4NTE7A_ER1pl_qiuxU4reI";
    }

    /**
     * @Given I have the payload:
     */
    public function iHaveThePayload(PyStringNode $string)
    {
        $this->payload = $string;
    }

    /**
     * @When /^I request "(GET|PUT|POST|DELETE|PATCH) ([^"]*)"$/
     */
    public function iRequest($httpMethod, $argument1)
    {
        $client = new GuzzleHttp\Client();
        $this->response = $client->request(
            $httpMethod,
            'http://community-poll.loc' . $argument1,
            [
                'body' => $this->payload,
                'headers' => [
                    "Authorization" => "Bearer {$this->bearerToken}",
                    "Content-Type" => "application/json",
                ],
            ]
        );
        $this->responseBody = $this->response->getBody(true);
    }

    /**
     * @Then /^I get a response$/
     */
    public function iGetAResponse()
    {
        if (empty($this->responseBody)) {
            throw new Exception('Did not get a response from the API');
        }
    }

    /**
     * @Given /^the response is JSON$/
     */
    public function theResponseIsJson()
    {
        $data = json_decode($this->responseBody);

        if (empty($data)) {
            throw new Exception("Response was not JSON\n" . $this->responseBody);
        }
    }

    /**
     * @Then the response contains :arg1 records
     */
    public function theResponseContainsRecords($arg1)
    {
        $data = json_decode($this->responseBody);
        $count = count($data);
        return $count == $arg1;
    }

    /**
     * @Then the question contains a title of :arg1
     * @throws Exception
     */
    public function theQuestionContainsATitleOf($arg1)
    {
        $data = json_decode($this->responseBody);
        if($data->title == $arg1) {

        } else {
            throw new Exception('The title does not match.');
        }
    }
}
