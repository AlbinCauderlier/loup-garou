		<form id="login" action="/controller/authentication/login.php" method="POST">	
			<div class="form-group mb-4">
				<div class="input-group">
					<div class="input-group-prepend">
						<div class="input-group-text"><i data-feather="mail"></i></div>
					</div>
					<input type="email" name="email-address" class="form-control" id="email-address" placeholder="Email address *" required autofocus>
				</div>
			</div>
			<div class="form-group mb-4">
				<div class="input-group">
					<div class="input-group-prepend">
						<div class="input-group-text"><i data-feather="lock"></i></div>
					</div>
					<input type="password" class="form-control" id="password" name="password" placeholder="Password *" required/>
				</div>
				<div class="forgot text-right">
					<a href="/forgotten-password/">Forgot password?</a>
				</div>
			</div>
			<button type="submit" class="btn btn-block btn-gradient">
				<i data-feather="log-in" class="mr-1"></i> Login
			</button>
		</form>