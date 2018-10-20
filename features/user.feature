Feature: User Feature
  Scenario: Adding new User
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "POST" request to "/api/v1/user/register" with body:
    """
    {
	"email": "test13@wp.pl",
	"username": "test14",
	"plainPassword": {
		"first": "test",
		"second": "test"
		}
    }
    """
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json"
    Then the response status code should be 200

  Scenario: Change Name User
    When i send changeName request

  Scenario: Change User Email
    When i send changeEmail request

  Scenario: Change User Password
    When i send changePassword request