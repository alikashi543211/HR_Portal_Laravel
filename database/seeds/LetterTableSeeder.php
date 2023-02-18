<?php

use App\Letter;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LetterTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('letters')->truncate();
        //
        $letters = array(
            // users
            array(
                'user_id' => 1,
                'title' => 'Offer Letter',
                'body' => '<p><img alt="" src="https://scontent.flhe21-1.fna.fbcdn.net/v/t1.6435-9/67565432_2584974795082606_8905524004316512256_n.jpg?_nc_cat=110&amp;ccb=1-5&amp;_nc_sid=09cbfe&amp;_nc_ohc=IYDOTOIMCwkAX9NMEeJ&amp;_nc_ht=scontent.flhe21-1.fna&amp;oh=00_AT8lbeS6Wcc3CrsGCneVaUQEbtV-arkWFaBfCKHP9rk7ZA&amp;oe=6244FE16" /></p>

                <hr />
                <p>Dear [Candidate_name],</p>

                <p>We&rsquo;re delighted to extend this offer of employment for the position of [Job_title] with [Company_name]. Please review this summary of terms and conditions for your anticipated employment with us.</p>

                <p>If you accept this offer, your start date will be [Start_Date] or another mutually agreed upon date, and you would report to [Manager_name].</p>

                <p>Please find attached the terms and conditions of your employment, should you accept this offer letter. We would like to have your response by [date]. In the meantime, please feel free to contact me or [Manager_name] via email or phone at [provide_contact_details], if you have any questions.</p>

                <p>We are all looking forward to having you on our team.</p>

                <p>Best regards,</p>

                <p>[Your_name]<br />
                [Signature]</p>'),
            array(
                'user_id' => 1,
                'title' => 'Increment Letter',
                'body' => '<p><img alt="" src="https://scontent.flhe21-1.fna.fbcdn.net/v/t1.6435-9/67565432_2584974795082606_8905524004316512256_n.jpg?_nc_cat=110&amp;ccb=1-5&amp;_nc_sid=09cbfe&amp;_nc_ohc=IYDOTOIMCwkAX9NMEeJ&amp;_nc_ht=scontent.flhe21-1.fna&amp;oh=00_AT8lbeS6Wcc3CrsGCneVaUQEbtV-arkWFaBfCKHP9rk7ZA&amp;oe=6244FE16" /></p>

                <hr />
                <p>Dear [Candidate_name],</p>

                <p>We&rsquo;re delighted to extend this offer of employment for the position of [Job_title] with [Company_name]. Please review this summary of terms and conditions for your anticipated employment with us.</p>

                <p>If you accept this offer, your start date will be [Start_Date] or another mutually agreed upon date, and you would report to [Manager_name].</p>

                <p>Please find attached the terms and conditions of your employment, should you accept this offer letter. We would like to have your response by [date]. In the meantime, please feel free to contact me or [Manager_name] via email or phone at [provide_contact_details], if you have any questions.</p>

                <p>We are all looking forward to having you on our team.</p>

                <p>Best regards,</p>

                <p>[Your_name]<br />
                [Signature]</p>'),

        );
        Letter::insert($letters);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
