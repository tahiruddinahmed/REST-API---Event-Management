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


