# 2025 Developer Test - Timothy Golding

Thank you for your interest in Glow Green. This test is designed to assess your skills and knowledge in PHP, Laravel, and general software development practices. Please complete the following tasks to the best of your ability.

We are especially interested the basic Laravel API that you build. Please do include comments in your code to explain your thought process and any assumptions you make.

We will discuss your solutions in detail during the interview.

## Questions

### What is the difference between public, protected and private in a class definition?


#### Answer
**public** Class definition implies that access to this classes methods or variables has no restricted visibility
**protected** Class definition implies that other members of the class or subclasses can access visibility to these methods or variables
**private class** Cefinitions implies that only members of the class can can access visibility to these methods or variables

### What is the difference between an interface and an abstract class?

#### Answer
An abstract class is used to define a set of methods that can be shared for related classes, while a interface class can be used to define a set of methods that must be implemented. Interfaces allow multiple inheritance, while abstract classes can have properties 

### Demonstrate how you would securely store and compare usernames and passwords using PHP and a MySQL Database.

#### Answer

**Pure PHP implementation**
To store passwords securely:

 - Use password_hash() to hash passwords.

 - Use password_verify() to validate credentials.

 - Always use prepared statements to prevent SQL injection.

 - Enforce HTTPS and consider adding 2FA for additional security.

```php
// Storing password
if ($_POST['password'] === $_POST['re_password']) {
    $username = $_POST['username']; // must be unique
    $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO user (username, password) VALUES (?, ?)");
    $stmt->execute([$username, $passwordHash]);
}

```
```php
// Validating user login
$username = $_POST['username'];
$password = $_POST['password'];

$stmt = $pdo->prepare("SELECT password FROM user WHERE username = ?");
$stmt->execute([$username]);

if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $storedHash = $row['password'];

    if (password_verify($password, $storedHash)) {
        echo "Login successful.";
    } else {
        echo "Invalid credentials.";
    }
}

```
**Laravel implementation**
Laravel uses Hash::make() and Hash::check() internally which in turn uses password_hash and password_verify.

```php
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

// Registering user
$user = User::create([
    'name' => $request->input('name'),
    'email' => $request->input('email'),
    'password' => Hash::make($request->input('password')),
]);

```
```php
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

// Authenticating user
$credentials = $request->only('email', 'password');

if (Auth::attempt($credentials)) {
    $request->session()->regenerate();
    return redirect()->intended('dashboard');
}

return back()->withErrors([
    'email' => 'The provided credentials do not match our records.',
]);
```


### What is the difference between a GET and POST request?

#### Answer

Get request method sends data in the query string, post data is sent in part of the payload. Never use GET request to send data like passwords or other sensitive information

### What is wrong with this query?

```php
$query = "SELECT * FROM table WHERE id = $_POST[ 'id' ]";
```

#### Answer

This query is missing protection from injections, assuming it's a numerical id, I would use type casting, PDO would be preferred, for older models mysql_real_escape_string() could be used to sanitise the id.
```php
$query = "SELECT * FROM table WHERE id =".((int)$_POST[ 'id' ]); // this would work
```
### Third party code

A requirement has come up to use a third party API, write some pros and cons for each of the following:

1. Writing your own bespoke wrapper for their API using PHP.
2. Using the APIs official PHP library downloaded from their site in a ZIP file.
3. Using a third-party library using Composer.

Which option would you have chosen, and why?

#### Answer

1. **Custom wrapper**
   - Tailored for your app’s needs
   - Slower to build/maintain
2. **Official PHP library **
   - Trusted by vendor
   - Might not update easily
3. **Composer package**
   - Easy updates, dependency tracking
   - Might include unneeded features

**Best choice: Composer**, if it’s available and maintained - otherwise fallback to official PHP library .

### What is wrong with this code?

```php
$haystack = "Hello world";

if (strpos($haystack, "Hello")) {
   echo "Found!";
}
```
#### Answer

The strpos function will return 0 so it will evaluate as false, I would use strstr() personally.
```php


// 

$haystack = "Hello world";

if (strstr($haystack, "Hello")) {
   echo "Found!";
}

// other option is to use !==false
if (strpos($haystack, "Hello") !== false) {
   echo "Found!";
}
```

### How would you implement rate limiting in a Laravel API?

#### Answer

I would add it as Throttle middleware in the route definition or rate limiter.

### How would you protect a web application from a DDoS attack?

#### Answer
- Rate limiting 
- Cloudflare
- Caching systems
- Monitoring Software

### What other attack vectors would you consider when building a web application?

#### Answer
- Flood Protection
- xss and other injections
- Securing folders / file permissions
- Moving data outside doc root
- Open relay protection on smtp relay services. 
- Updates and patches on all third party modules and cores.
- CSRF / CORS
- Content Security Policies

### How would you refactor this controller method?

```php
public function store(Request $request)
{
    $data = $request->all();
    $data['slug'] = Str::slug($data['title']);
    Boiler::create($data);
    Mail::to('admin@example.com')->send(new BoilerCreated($data));
    return response()->json(['success' => true]);
}
```

#### Answere
I would add a try catch around the create and consider database transaction logic with rollback, if committed then I would send the email.
Does the json response indicate that the data was created or that the email was sent? I would consider moving the email to a different method or adding the email sent status in the json. 

## Build a basic Laravel API

Create a Laravel Application with the following features:

1. Retrieves a list of boilers from Glow Green's API (details below).
2. Stores the list of boilers in a relational database. There are 8 boilers in total, but only 3 manufacturers, and only 2 fuel types. Ensure the data structure is normalized to reduce data redundancy and ensure data integrity.
3. Implements a simple REST API to retrieve the list of boilers from your database.
   1. There should be an index endpoint to list all. You could add search filters for manufacturer and other properties to this endpoint.
   2. There should be a get endpoint to retrieve a single boiler by its ID.
   3. There should be an update endpoint to update a boiler by its ID.
   4. There should be a delete endpoint to soft delete a boiler by its ID.
   5. There should be a create endpoint to add a new boiler.
   6. You might want to consider adding an authentication layer for applications querying your API, or for individual users, or both!
   7. You might want to consider writing tests for your API endpoints.

### Glow Green's Boiler API Endpoint

GET https://api.glowgreenltd.com/api/2025-test/boilers

You will need to authenticate using an API key in the request header. The header is `X-GG-API-Key`. Your API key will be provided to you separately.

