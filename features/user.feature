Feature: User Feature
  Scenario: Adding new User
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "POST" request to "/api/v1/register" with body:
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
    Then the header "Content-Type" should be equal to "application/json"