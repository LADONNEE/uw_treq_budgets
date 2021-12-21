<ul class="jump-nav mt-2">
    <li>
        <a href="#nav-tasks">
            Needs Approval <span class="badge">{{ $reports->tasks->count }}</span>
        </a>
    </li>

    @if(hasRole('workday'))
        <li>
            <a href="#nav-revisits">
                Revisits <span class="badge">{{ $reports->revisits->count }}</span>
            </a>
        </li>
    @endif

    @if(hasRole('budget:fiscal'))
        <li>
            <a href="#nav-my-reports">
                My Reports <span class="badge">{{ $reports->myEffortReports->count }}</span>
            </a>
        </li>

        <li>
            <a href="#nav-my-faculty">
                My Faculty <span class="badge">{{ $reports->myFaculty->count }}</span>
            </a>
        </li>

        <li>
            <a href="#nav-faculty">
                All Faculty <span class="badge"></span>
            </a>
        </li>
    @endif
</ul>
