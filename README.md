# Laravel Queue Mail Sender
<h3>The process of sending mail at regular intervals with the help of a queue</h3>
<li>1-Composer Update </li>

<li>2-php artisan key:generate</li>

<li>3-php artisan migrate (to add tables)
    
        create_jobs_table
        create_failed_jobs_table

</li>

<li>4-Add your mail information to your env file 
    
       
        MAIL_MAILER=smtp
        MAIL_HOST=smtp.gmail.com
        MAIL_PORT=587
        MAIL_USERNAME=YOUR MAIL ADDRESS
        MAIL_PASSWORD="YOUR PASSWORD"
        MAIL_ENCRYPTION=tls
        MAIL_FROM_ADDRESS=YOUR MAIL ADDRESS
        MAIL_FROM_NAME="MAIL NAME
</li>

<li>5-php artisan serve (to run your project)</li>

<li>6-php artisan queue:work (to run the queue)</li>

<li>You can change the sending time according to your request. [app->Http->Controllers->JobController.php]
    
        $emailJob = (new SendEmail($details, $id))->delay(Carbon::now()->addSeconds(5));
</li>

<li>You can set execution time. [app->Http->Controllers->JobController]
       
        php ini_set('max_execution_time', 1000000);
</li>
