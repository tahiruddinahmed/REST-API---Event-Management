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



