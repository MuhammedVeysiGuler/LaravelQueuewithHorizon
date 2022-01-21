# LaravelQueueMailSender
<li>1-Composer Update </li>

<li>2-php artisan key:generate</li>

<li>3-php artisan migrate (to add tables)</li>

<li>4-Add your mail information to your env file <br>
    &nbspMAIL_MAILER=smtp<br>
    &nbspMAIL_HOST=smtp.gmail.com<br>
    &nbspMAIL_PORT=587<br>
    &nbspMAIL_USERNAME=YOUR MAIL ADDRESS<br>
    &nbspMAIL_PASSWORD="YOUR PASSWORD"<br>
    &nbspMAIL_ENCRYPTION=tls<br>
    &nbspMAIL_FROM_ADDRESS=YOUR MAIL ADDRESS<br>
    &nbspMAIL_FROM_NAME="MAIL NAME"<br></li>

<li>5-php artisan serve (to run your project)</li>

<li>6-php artisan queue:work (to run the queue)</li>

<h3> 
You can change the sending time according to your request. app->Http->Controllers->JobController.php
      <li>$emailJob = (new SendEmail($details, $id))->delay(Carbon::now()->addSeconds(5));</li>
</h3>



<h3> 
You can set execution time. app->Http->Controllers->JobController <br>
    <li>php ini_set('max_execution_time', 1000000); </li>
</h3>

