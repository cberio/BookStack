<?php

namespace BookStack\Notifications;

use BookStack\Page;
use BookStack\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PageUpdated extends Notification
{
    protected $page;

    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Page $page)
    {
        $this->page = $page;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    public function toSlack($notifiable)
    {
        $prev_revision = $this->page->getPreviousRevision();

        if (empty($prev_revision)){
            /*
             * send notification when new page created
            */

            return (new SlackMessage())
                ->success()
                ->content(':alarm_clock: hello-wiki new page created:alarm_clock:')
                ->attachment(function ($attachment){
                    $attachment->title($this->page->name, $this->page->getUrl())
                        ->fields([
                            '제목' => $this->page->name,
                            'Book' => $this->page->book->name,
                            'Chapter' => $this->page->chapter->name,
                            '작성자' => User::find($this->page->created_by)->name,
                            '수정자' => User::find($this->page->updated_by)->name
                        ]);
                });
        } else {

            return (new SlackMessage())
                ->success()
                ->content(':alarm_clock: hello-wiki new page updated:alarm_clock:')
                ->attachment(function ($attachment) {
                    $attachment->title($this->page->name, $this->page->getUrl())
                        ->fields([
                            '제목' => $this->page->name,
                            'Book' => $this->page->book->name,
                            'Chapter' => $this->page->chapter->name,
                            '작성자' => User::find($this->page->created_by)->name,
                            '수정자' => User::find($this->page->updated_by)->name
                        ]);
                });
        }
    }


    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', 'https://laravel.com')
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
