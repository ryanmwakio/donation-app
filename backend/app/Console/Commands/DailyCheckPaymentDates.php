<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use SendGrid\Mail\TypeException;

class DailyCheckPaymentDates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:payment-dates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Artisan command to check payment dates';

    /**
     * Execute the console command.
     *
     * @return void
     * @throws TypeException
     */
    public function handle()
    {
      //check payment dates for all donors from donors table
        $donors = \App\Models\Donor::all();
        foreach ($donors as $donor) {
            if ($donor->next_payment_date == null) {
                    $donor->payment_date = null;
                    $donor->save();
            }else{
                //check if the next_payment_date which is formated by carbon is today if today echo the donor id
                if ($donor->next_payment_date->isToday()) {
                    $initiate_payment_url = env('ROOT_URL').'/api/v1/donate-via-pesapal/{'.$donor->id.'}';

                    $email = new \SendGrid\Mail\Mail();
                    $email->setFrom("ryanmwakio6@gmail.com", "Pesapal Ryan");
                    $email->setSubject(ucfirst($donor->period_of_donation) . " donation payment for " . $donor->name . " set up successfully");
                    $email->addTo($donor->email, $donor->name);
                    $email->addContent("text/plain", "Thank you for choosing to donate,You can now make payment using pesapal.");
                    $email->addContent(
                        "text/html", "<h1>Thank you for continuing to donate.</h1><br><style>h1{color:red;}</style><p>Your donation day is today, click to get the payment email <a href=".$initiate_payment_url." style='color: #0072b3'>pesapal payment link</a></p><hr><img src='https://res.cloudinary.com/dwxeqcqt0/image/upload/v1654351754/imageedit_1_2429230768_tg9nee.png' alt=''/>"
                    );
                    $sendgrid = new \SendGrid(env('SENDGRID_API_KEY'));

                    $sendgrid->send($email);

                }

            }
        }
    }
}
