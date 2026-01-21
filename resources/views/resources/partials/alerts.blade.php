@if(session('success'))
    <div style="color:green; margin-bottom:10px;">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div style="color:red; margin-bottom:10px;">
        {{ session('error') }}
    </div>
@endif
