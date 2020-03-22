							<form id="forgotten-password" action="/controller/authentication/forgotten-password.php" method="POST">    
								<div class="form-group mb-4">
									<div class="input-group mb-2">
									<div class="input-group-prepend">
										<div class="input-group-text">
											<i data-feather="mail"></i>
										</div>
									</div>
									<input type="email" name="email-address" class="form-control" id="email-address" placeholder="Email Address *" required />
									</div>
								</div>
								<button type="submit" class="btn btn-block btn-gradient">
									Reset Password
								</button>
							</form>