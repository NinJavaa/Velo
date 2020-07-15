Feature: To ensure te API is responding in a simple manner

  In order to build interchangeable front ends
  As a JSON API developer
  I need to allow Create, Read, Update, and Delete functionality

  Background:
    Given there are Event with the following details:
      | rep              | dateRep | status  | commentPermession | showInviteList | isArchived | showDate | event |
      | 1                | 5454555 | pending |  true             | true           | true       | 56511565 |  51   |


  Scenario: Can get a Single EventConfig
    Given I request "/config/getConfig/2" using HTTP GET
    Then the response code is 200
    And the response body contains JSON:
     """
{
         "id": 2,
        "rep": 4,
        "dateRep": "1970-06-28T22:01:24+01:00",
        "status": "pending",
        "commentPermession": null,
        "showInviteList": null,
        "isArchived": true,
        "showDate": "1970-06-28T22:01:24+01:00",
        "event": {
            "id": 1,
            "distance": 454116651,
            "location": "bro",
            "startDate": "1970-01-19T03:58:14+01:00",
            "endDate": "1970-01-08T13:16:35+01:00",
            "eventName": "dfdfdfdf",
            "isTheme": true,
            "eventConfig": 2,
            "__initializer__": null,
            "__cloner__": null,
            "__isInitialized__": true
    }
}
    """

  Scenario: Can delete Single EventConfig
    Given I request "/config/deleteConfig/2" using HTTP DELETE
    Then the response code is 200
    And the response body contains JSON:
        """
      {
    "response" : "Deleted Successfully"
       }
    """