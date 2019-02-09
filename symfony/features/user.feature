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
    Then the response status code should be 201

  Scenario: Confirmed new User
    When I have user in database
    And I send confirm request
    Then I user was confirmed

  Scenario: Ban user
    When I have user in database
    And I send banned request
    Then I have banned user

  Scenario: Change user email
    When I have user in database
    And I send change user email request
    Then I have user with changed email

  Scenario: Change user name
    When I have user in database
    And I send change user name request
    Then I have user with changed name

  Scenario: Change user password
    When I have user in database
    And I send change password resquest
    And I have user with changed password

  Scenario: Granted Admin User role
    When I have user in database
    And I send granted request
    Then I have other admin

