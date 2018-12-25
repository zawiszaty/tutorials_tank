Feature: User Feature
  Scenario: Adding new User
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "POST" request to "/api/v1/user/register" with body:
    """
    {
	"email": "test@wp.pl",
	"username": "test",
	"plainPassword": {
		"first": "test123",
		"second": "test123"
		}
    }
    """
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json"
    Then the response status code should be 200