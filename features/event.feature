Feature: To ensure te API is responding in a simple manner

  In order to build interchangeable front ends
  As a JSON API developer
  I need to allow Create, Read, Update, and Delete functionality

  Background:
    Given there are Event with the following details:
      | eventName                             | distance | location | startDate | endDate | isTheme |
      | some fake album name                  | 1000     | paris    |  4845415  | 3411861 | true    |


  Scenario: Can get a Single Album
    Given I request "/postEvent" using HTTP POST
    Given the request body is:
    """
      {
            "eventName": "some fake album name",
            "distance": 1000,
            "location": "paris",
            "startDate": 4845415,
            "endDate": 3411861,
            "isTheme": true
       }
    """

    Then the response code is 200
    And the response body contains JSON:
     """
    {
          "id": 69,
          "distance": 1000,
          "location": "paris",
          "startDate": "1970-02-26T02:56:55+01:00",
          "endDate": "1970-02-09T12:44:21+01:00",
          "eventName": "some fake album name",
          "isTheme": true,
          "eventConfig": null

    }
    """


  Scenario: Can get a Single Album
    Given I request "/getEvent/4" using HTTP GET
    Then the response code is 200
    And the response body contains JSON:
     """
    {
          "id": 4,
          "distance": 1000,
          "location": "paris",
          "startDate": "1970-02-26T12:07:37+01:00",
          "endDate": "1970-02-26T02:47:28+01:00",
          "eventName": "now that's what i call album vol2",
          "isTheme": true,
          "eventConfig": null
    }
    """
