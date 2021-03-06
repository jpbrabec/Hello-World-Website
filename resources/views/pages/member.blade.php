@extends("app")

@section("content")

<div class="section"><div class='section-container'>
	<h3>Member - {{ $member->name }}</h3>
	
	@if ($member->id == session()->get('member_id') || session()->get('authenticated_admin') == "true" || isset($setPassword) ) {{-- Edit Profile --}}
	<div class="panel panel-default">
		<form method="post" action="/member/{{ $member->id }}" enctype="multipart/form-data" class="panel-body validate">
			{!! csrf_field() !!}
			<p>Fields marked with an * are required</p>
			<label for="memberName">Full Name * </label>
			<input type="text" name="memberName" id="memberName" placeholder="Full Name" value="{{ $member->name }}" class="form-control" data-bvalidator="required" data-bvalidator-msg="Please enter your full name">
			<br>
			<label for="picture">Profile Picture (JPG or PNG)</label>
			@if ($member->picture)
			<a href="{{ $member->picturePath() }}" class="form-control">{{ $member->picture }}</a>
			@endif
			<input type="file" name="picture" id="picture" class="form-control">
			<br>
			<label for="email">Account Email *</label>
			<input type="text" name="email" id="email" placeholder="Email" value="{{ $member->email }}" class="form-control" data-bvalidator="required,email" data-bvalidator-msg="An email is required for your account.">
			<br>
			@if (isset($setPassword))
			<label for="password">Password</label>
			<input type="password" name="password" id="password" placeholder="Password" class="form-control" data-bvalidator="required" data-bvalidator-msg="A password is required">
			<input type="hidden" name="reset_token" value="{{ $reset_token }}">
			<br>
			<label for="confirmPassword">Confirm Password</label>
			<input type="password" name="confirmPassword" id="confirmPassword" placeholder="Confirm Password" class="form-control" data-bvalidator="required,equalto[password]" data-bvalidator-msg="Password does not match">
			<br>
			@endif
			<label for="email_public">Public Email</label>
			<input type="text" name="email_public" id="email_public" placeholder="Public Email" value="{{ $member->email_public }}" class="form-control" data-bvalidator="email" data-bvalidator-msg="Please enter a valid email address. (Optional)">
			<br>
			<label for="description">Public Message</label>
			<textarea name="description" id="description" class="form-control" placeholder="Public Message">{{ $member->description }}</textarea>
			<br>
			<label for="gradYear">Year of Graduation *</label>
			<input type="number" name="gradYear" id="gradYear" placeholder="Graduation Year" value="{{ $member->graduation_year}}" class="form-control" data-bvalidator="required,number" data-bvalidator-msg="A graduation year is required">
			<br>
			<label for="major">Major</label>
			<select name="major" id="major" class="form-control" {!! $member->major_id ? 'data-bvalidator="required"' : "" !!}>
				<option value="">Select</option>
				@foreach ($majors as $major)
				<option value="{{ $major->id }}" {{ $member->major_id==$major->id ? "selected":"" }}>{{ $major->name }}</option>
				@endforeach
			</select>
			<br>
			<label for="gender">Gender</label>
			<select name="gender" id="gender" class="form-control" {!! $member->gender != "" ? 'data-bvalidator="required"' : "" !!}>
				<option value="">Select</option>
				<option value="Female" {{ $member->gender=="Female" ? "selected":"" }}>Female</option>
				<option value="Male" {{ $member->gender=="Male" ? "selected":"" }}>Male</option>
				<option value="Other" {{ $member->gender=="Other" ? "selected":"" }}>Other</option>
				<option value="No" {{ $member->gender=="No" ? "selected":"" }}>Prefer Not To Answer</option>
			</select>
			<br>
			<label for="facebook">Facebook Profile</label>
			<input type="text" name="facebook" id="facebook" placeholder="Facebook Profile" value="{{ $member->facebook }}" class="form-control" data-bvalidator="url" data-bvalidator-msg="Please enter a valid URL to your Facebook Profile.">
			<br>
			<label for="github">Github Profile</label>
			<input type="text" name="github" id="github" placeholder="Github Profile" value="{{ $member->github }}" class="form-control" data-bvalidator="url" data-bvalidator-msg="Please enter a valid URL to your Github Profile.">
			<br>
			<label for="linkedin">LinkedIn Profile</label>
			<input type="text" name="linkedin" id="linkedin" placeholder="LinkedIn Profile" value="{{ $member->linkedin }}" class="form-control" data-bvalidator="url" data-bvalidator-msg="Please enter a valid URL to your LinkedIn Profile.">
			<br>
			<label for="devpost">Devpost Profile</label>
			<input type="text" name="devpost" id="devpost" placeholder="Devpost Profile" value="{{ $member->devpost }}" class="form-control" data-bvalidator="url" data-bvalidator-msg="Please enter a valid URL to your Devpost Profile.">
			<br>
			<label for="website">Personal Website</label>
			<input type="text" name="website" id="website" placeholder="Personal Website" value="{{ $member->website }}" class="form-control" data-bvalidator="url" data-bvalidator-msg="Please enter a valid URL to your Personal Website.">
			<br>
			<label for="resume">Resume (PDF)</label>
			@if ($member->resume)
			<a href="{{ $member->resumePath() }}" class="form-control">{{ $member->resume }}</a>
			@endif
			<input type="file" name="resume" id="resume" class="form-control">
			<br>
			@if (!isset($setPassword))
				@if(session()->get('authenticated_admin') == "true")
				<a href="/reset/{{ $member->id }}/{{ $member->reset_token() }}" class="btn btn-warning pull-left">Reset Password</a>
				@elseif($member->id == session()->get('member_id'))
				<a href="/reset/{{ $member->id }}/{{ $member->reset_token() }}" class="btn btn-warning pull-left">Change Password</a>
				@endif
			@endif
			<input type="submit" value="Update Profile" class="btn btn-primary pull-right">
		</form>
	</div>
	@else {{-- View Profile --}}
	<div class="panel panel-default text-left">
		<div class="panel-body">
			<b>Name:</b> {{ $member->name }}<br>
			@if ($member->email_public)
			<b>Email:</b> {{ $member->email_public }}<br>
			@endif
			@if ($member->major)
			<b>Major:</b> {{ $member->major->name }}<br>
			@endif
			@if ($member->description)
			<b>About:</b> {{ $member->description }}<br>
			@endif
			@if ($member->facebook)
			<b>Facebook Profile:</b> <a href="{{ $member->facebook }}">{{ $member->facebook }}</a><br>
			@endif
			@if ($member->github)
			<b>Github Profile:</b> <a href="{{ $member->github }}">{{ $member->github }}</a><br>
			@endif
			@if ($member->linkedin)
			<b>LinkedIn Profile:</b> <a href="{{ $member->linkedin }}">{{ $member->linkedin }}</a><br>
			@endif
			@if ($member->devpost)
			<b>Devpost Profile:</b> <a href="{{ $member->devpost }}">{{ $member->devpost }}</a><br>
			@endif
			@if ($member->website)
			<b>Personal Website:</b> <a href="{{ $member->website }}">{{ $member->website }}</a><br>
			@endif
		</div>
	</div>
	@endif
</div></div>

@stop