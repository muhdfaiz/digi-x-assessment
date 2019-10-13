## Section 1: Application Programming Interface

##### a. Using simple code, explain what kind of situations would you use the methods:

i. GET

- I will use GET method when to retrieve a representation of a resource. GET request do not change the state of the resource.
Resource should never be modified on the server side when you use a GET request. For example when you have a Task resource
and you want to retrieve all the task available.

<br>

```markdown
public function index()
{
    $tasks = Task::paginate(15);

    return response()->json($tasks);
}
```

<br>

ii. POST

- I will use `POST` method when attempting to create a new resource within a collection. 
POST request body contains a information about the new resource to be added in the server. For example when you want to create
a new task in your To Do List application.

<br>

```markdown
public function store(Request $request)
{
    $task = new Task;
    $task->name = $request->name;
    $task->description = $request->description;
    $task->save();

    return response()->json($task);
}
```

<br>

iii. UPDATE

- In API, there is no request method call `UPDATE`. You can refer here. Only have `GET`, `HEAD`, `POST`, `PUT`, `DELETE`, `CONNECT`, `OPTIONS`, `TRACE` and `PATCH`.
  - [https://tools.ietf.org/html/rfc7231#section-4](https://tools.ietf.org/html/rfc7231#section-4)
  - [https://tools.ietf.org/html/rfc5789#section-2](https://tools.ietf.org/html/rfc5789#section-2)

<br>

iv. PUT
- I will use `PUT` method when you want to replace or update all current representations of the resource. Some people confuse between
PUT and PATCH. Let say we have a Task resource contain `name` and `description`. When you want to update `name` and `description`,
use `PUT`. If you want to update one of the atrribute, use `PATCH`. In `PUT` method request, when the resource not available in
the server, `PUT` request must create a new resource.

```markdown
public function store($taskID, Request $request)
{
    $task = Task::find($taskID);
    $task->name = $request->name;
    $task->description = $request->description;
    $task->save();

    return response()->json($task);
}
```

<br>

##### b. Explain in your own words, what kind of “authentication” works best for a web service
that needs to be secure, yet easy to implement across different programming languages.
You may want to give an example of how to call this API.

- In my opinion, The best authentication for a web service is `OAuth`. One of the advantages of `OAuth` is the ability to revoke the access
token and scope the token to specific permission. 

- For example, you want to limit the access token permission to read only specific resource.
In `OAuth` when you want to revoke the token, you can do it easily because the token exist in the database. You can delete the token
or set the token into revoked status. 

- `OAuth` token also have expiration time. For better security, you can set the token only valid for a short
period. Third party application can use the refresh token when the token expired to get a new token.
If the refresh token is compromised it is useless because the hacker doesn't have access to the client id which must be sent to the authentication server at the same time to get a new access token.

- Imagine you have one API and used basic authentication to secure the API, where the username and password are sent in the request.
The drawback for this method is that is username and password are being sent in the request.

- How to get the access token. `OAuth` have multiple grant types. Example below for `client_credential` grant
```markdown
curl --location --request POST "https://example.com/api/v1/oauth/token" \
  --form "grant_type=client_credentials" \
  --form "client_id=1" \
  --form "client_secret=Gsv7brPlt5fIpqa1ebFc5A1uHwDJBPY6TRaMm8wa"
```

- How to use the access token. Specify the access token in the header like below.

```markdown
curl -H "Authorization: token OAUTH-TOKEN" https://api.example.com/api/v1/
```

<br>

##### c. What kind of format is best to be returned by an API as a response? Explain your answer and provide comparisons, if needed.

- The best format is using JSON. One of the important criteria for me is perfomance. JSON is much more faster because don't have
unnecessary tag like XML. It will make the document size compact. 

- Another reason is JSON data is easier to read because structure
very straightforward. JSON data structure is a map whereas XML is a tree. 

- One of the advantages of XML is support multiple type
of data such as images but it can't match with what JSON can offer you. JSON also native to javascript. So you'd have to write less code
on the client side and easier to work with.

<br>

- Comparisons

| XML                                                                                                                                       | JSON                                                                                                                                                          |
|-------------------------------------------------------------------------------------------------------------------------------------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------|
| Bulky and slow in parsing, leading to slower data transmission                                                                            | Very fast as the size of file is considerably small, faster parsing by the JavaScript engine and hence faster transfer of data                                |
| Document size is bulky and with big files, the tag structure makes it huge and complex to read.                                           | Compact and easy to read, no redundant or empty tags or data, making the file look simple.                                                                    |
| Supports many complex data types including charts, images and other non-primitive data types.                                             | JSON supports only strings, numbers, arrays Boolean and object. Even object can only contain primitive types.                                                 |
| XML supports UTF-8 and UTF-16 encodings.                                                                                                  | JSON supports UTF as well as ASCII encodings.                                                                                                                 |
| Though the X is AJAX stands for XML, because of the tags in XML, a lot of bandwidth is unnecessarily consumed, making AJAX requests slow. | As data is serially processed in JSON, using it with AJAX ensures faster processing and hence preferable. Data can be easily manipulated using eval() method. |