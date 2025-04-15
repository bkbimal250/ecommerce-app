<footer class="bg-dark text-white mt-5 py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-3 mb-4 mb-md-0">
                <h5>{{ config('app.name', 'Laravel E-Commerce') }}</h5>
                <p class="text-muted">Your one-stop shop for all products. Quality guaranteed with fast delivery and excellent customer service.</p>
                <div class="social-icons mt-3">
                    <a href="#" class="text-white me-2"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-white me-2"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-white me-2"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-white"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            <div class="col-md-3 mb-4 mb-md-0">
                <h5>Quick Links</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
                    <li class="mb-2"><a href="{{ route('products.index') }}" class="text-decoration-none text-muted">Shop</a></li>
                    <li class="mb-2"><a href="#" class="text-decoration-none text-muted">About Us</a></li>
                    <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Contact</a></li>
                </ul>
            </div>
            <div class="col-md-3 mb-4 mb-md-0">
                <h5>Customer Service</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Help Center</a></li>
                    <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Track Order</a></li>
                    <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Return & Refund</a></li>
                    <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Shipping Info</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <h5>Newsletter</h5>
                <p class="text-muted">Subscribe to our newsletter for the latest updates and offers.</p>
                <form>
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="Email Address" aria-label="Email Address">
                        <button class="btn btn-primary" type="button">Subscribe</button>
                    </div>
                </form>
            </div>
        </div>
        <hr class="my-4 bg-secondary">
        <div class="row">
            <div class="col-md-6 text-center text-md-start">
                <p class="mb-0 text-muted">&copy; {{ date('Y') }} {{ config('app.name', 'Laravel E-Commerce') }}. All rights reserved.</p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <p class="mb-0 text-muted">
                    <a href="#" class="text-decoration-none text-muted me-3">Privacy Policy</a>
                    <a href="#" class="text-decoration-none text-muted me-3">Terms of Service</a>
                    <a href="#" class="text-decoration-none text-muted">Cookies Settings</a>
                </p>
            </div>
        </div>
    </div>
</footer>