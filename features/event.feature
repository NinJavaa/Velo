Feature: To ensure te API is responding in a simple manner

  In order to build interchangeable front ends
  As a JSON API developer
  I need to allow Create, Read, Update, and Delete functionality

  Background:
    Given there are Event with the following details:
      | eventName                             | distance | location | startDate | endDate | isTheme |
      | some fake album name                  | 1000     | paris    |  4845415  | 3411861 | true    |


  Scenario: Can post a Single Event
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
          "id": 15,
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
    Given I request "/getEvent/1" using HTTP GET
    Then the response code is 200
    And the response body contains JSON:
     """
    {
    "id": 1,
    "distance": 454116651,
    "location": "bro",
    "startDate": "1970-01-19T03:58:14+01:00",
    "endDate": "1970-01-08T13:16:35+01:00",
    "eventName": "dfdfdfdf",
    "isTheme": true,
    "eventConfig": null
    }
    """
