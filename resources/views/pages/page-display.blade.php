<div ng-non-bindable>

    <h1 id="bkmrk-page-title" class="float left">
        {{$page->name}}
    </h1>
    <a href="{{ url()->current() }}#disqus_thread" style="float:right; margin-top: 30px;">Link</a>

    <div style="clear:left;"></div>

    @if (isset($diff) && $diff)
        {!! $diff !!}
    @else
        {!! $page->html !!}
    @endif
</div>