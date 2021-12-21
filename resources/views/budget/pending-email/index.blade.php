@extends('layout/htmlpage')
@section('title', 'Pending Email')
@section('content')
    <div class="panel-area container">
        <section class="panel">

            <h1>Pending Email</h1>

            <p>
                Effort sends email on a delay to reduce excess notifications for tasks that were quickly
                completed, orders that were quickly canceled or completed, or mistakes in data entry.
            </p>

            <p>
                Effort sends pending email notifications once every 15 minutes. During each email cycle Effort
                sends notifications for Approvals that were added at least 15 minutes ago. In the
                extreme case an Approval notification email may not be sent for 29 minutes and 59 seconds.
            </p>

            @if($last)
                <div class="alert alert-info my-4">Email was last sent {{ $last }}.</div>
            @endif

            <p>
                Following are Approval, Completion, and Sent Back notices in the system on pending Effort Reports where the email has not
                yet been sent. These emails will be sent in the next batch after the notification is 15
                minutes old.
            </p>

            @if(count($report) > 0)

                <table class="table-tight">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Email To</th>
                        <th>Notification Type</th>
                    </tr>
                    </thead>

                    @foreach($report as $item)

                        <tr>
                            <td>{{ $item->report_id }}</td>
                            <td><a href="{{ route('effort-report-show', [$item->faculty_id, $item->report_id]) }}">{{ $item->title }}</a></td>
                            <td>{{ $item->to }}</td>
                            <td>{{ $item->type }}</td>
                        </tr>

                    @endforeach
                </table>

            @else

                <div class="empty-table">There are no pending messages.</div>

            @endif


        </section>
    </div>
@stop
