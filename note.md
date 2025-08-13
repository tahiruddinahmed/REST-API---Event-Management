# REST API - event management

## Step 1
Create a laravel project. 

1. Laravel 11 has no `api.php` in the `routes/` directory. Install it using 
    ````
    php artisan install:api
    ````

2. Creating the models file 
    ```
    php artisan make:models Event -m;

    php artisan make:models Attendee -m;
    ```

3. Database column in the `Database/Migration/` folder
   ```
    Event 

      -- relation to User table (one user can make/create multiple events)
      -- string->title - title of the event 
      -- description 
      -- start_time 
      -- end_time 

    Attendee 
      -- relation to User table 
      -- Realtion to event table - must belongs to a events
   ```
4. Define this relation in our laravel project. in order to do that we will do that in `models` class
    ```
    App\Models\Event

        - events belongsTo user
        - events hasMany to attendees

    App\Models\Attendee
        - Attendee belongsTo user
        - attendee belongsTo events
    ```
   

5. Let's create the `controllers`
    ``` 
      php artisan make:controller EventController --api

      php artisan make:controller AttendeeController --api
    ```
✅ Correct usage of --api flag – it creates controllers without view-related methods like create/edit.


6. Now let's define some route in the  `routes/api.php` 

  ```
    Route::apiResource('events', EventController::class);
Route::apiResource('events.attendees', AttendeeController::class)
    ->scoped(['attendee' => 'event']);
  ```

## API Resource 
An API Resource is a class that formats data before sending it as a json response. This is useful when building an API where you want to control how your model data will appears in the response. 
 - It acts like a `Transformer`

### Generating API Resource 
To generate a resource class, you may need to use the  `make:resource` artisan command 

```
php artisan make:resource EventResource
php artisan make:resource UserResource
php artisan make:resource AttendeeResource
```

```php
 public function toArray(Request $request): array
    {
        return [
            
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'user' => new UserResource($this->whenLoaded('user')),
            'attendees' => AttendeeResource::collection(
                $this->whenLoaded('attendees')
            )
        ];
    }
```
Now You have full controll, how your data appears in JSON.

## Traits 
 Traits are a PHP feature used to promote code reuse by allowing developers to group reusable methods in a single place and then include them in multiple classes. Traits are similar to classes but are designed to be included in other classes to extend their functionality without inheritance. This is particularly useful in 
 Laravel, where traits are commonly used to share functionality across controllers, models, or other classes.

## Santum 
In Laravel, Sanctum is a lightweight authentication package designed to manage API authentication and issue API tokens for users, allowing secure access to your application's API endpoints. It is particularly useful for single-page applications (SPAs), mobile apps, or any client that needs to authenticate with a Laravel backend.

Read more: https://laravel.com/docs/12.x/sanctum


## Throttle API 
 `throttle:api` refers to API Throttling, a technique used to limit the number of requests a client can make to an API within a specified time frame. Its primary purpose is to 

 1. Prevent Abuse and overuse: It safegaurds the API from malicious attacks like Denial-of-Service(DoS) and prevent any single user or application from consuming excessive resources, which could negatively impact the performance for other users. 


When a client exceed the defined throttlling limits, the API typically responds with an HTTP status code like `429 Too Many Requests`, often including information in the response headers (e.g, `Retry-After`) indicating when the client can make further requests, Throttling can be implemented at various levels, such as per-user, per-application, or per-id address.

## Policies (Permisions)
In laravel, Policies are classes designed to organize and manage authorization logic, specifically for actions performed on Eloquent models or other resources within an application, They provide a structured way to determine if a user has the necessary permissions to perform a particular action, such as `creating`, `viewing`, `updating`, or `deleting` a spacific resource. 

Here's a breakdown of key aspects of Laravel Policies:
 1. Model-Centric Authorization: Policies are typically associated with a specific model (e.g., PostPolicy for the Post model). This allows for encapsulating all authorization rules related to that model within a single, dedicated class.
 2. Methods for Actions: A policy class contains methods, each corresponding to a specific action a user might perform on the associated model (e.g., create, view, update, delete). These methods receive the currently authenticated User object and, for actions like update or delete, also receive the instance of the model being acted upon.
 3. Returning Boolean Values: Each policy method returns a boolean value (true or false) indicating whether the user is authorized to perform the action.
 4. before() Method: Policies can optionally include a before() method, which runs before any other policy method. This is useful for implementing global authorization checks, such as granting full access to "super administrators" regardless of other specific permissions.
 5. Integration with Gates: While Policies are model-specific, Laravel also offers "Gates" for more general authorization checks not directly tied to a model. Policies can be seen as a more organized and scalable alternative to Gates when dealing with authorization on resources.
 6. Usage in Controllers and Blade: Policies are typically used in controllers (e.g., using the authorize() helper) to restrict access to actions based on user permissions. They can also be used in Blade templates to conditionally display or hide elements based on authorization.
By centralizing authorization logic within Policy classes, Laravel promotes clean code, reusability, and easier maintenance of access control rules in applications.



