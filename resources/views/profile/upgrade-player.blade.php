@extends('layouts.app')

@section('title', __('app.auth.player_upgrade_title') . ' - ' . __('app.name'))

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">{{ __('app.auth.player_upgrade_title') }}</h4>
                    <p class="text-muted mb-0">{{ __('app.auth.player_upgrade_description') }}</p>
                </div>
                
                <div class="card-body">
                    <div id="alert-container"></div>
                    
                    <form id="upgradeForm">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="gaming_nickname" class="form-label">
                                {{ __('app.auth.nickname') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="gaming_nickname" 
                                   name="gaming_nickname" 
                                   required
                                   placeholder="Enter your gaming nickname">
                            <div class="form-text">This will be your display name in games and tournaments</div>
                        </div>

                        <div class="mb-3">
                            <label for="team_preference" class="form-label">{{ __('app.auth.team_preference') }}</label>
                            <textarea class="form-control" 
                                      id="team_preference" 
                                      name="team_preference" 
                                      rows="3"
                                      placeholder="Describe your team preferences, play style, etc."></textarea>
                            <div class="form-text">Optional: Tell us about your team preferences</div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">{{ __('app.auth.description') }}</label>
                            <textarea class="form-control" 
                                      id="description" 
                                      name="description" 
                                      rows="4"
                                      placeholder="Tell us about yourself, your gaming experience, etc."></textarea>
                            <div class="form-text">Optional: Tell us about yourself</div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('profile.show') }}" class="btn btn-secondary me-md-2">
                                {{ __('app.common.cancel') }}
                            </a>
                            <button type="submit" class="btn btn-primary" id="upgradeBtn">
                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                {{ __('app.auth.submit_upgrade') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border-radius: 0.5rem;
    }
    
    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 0.5rem 0.5rem 0 0 !important;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    }
    
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('upgradeForm');
        const submitBtn = document.getElementById('upgradeBtn');
        const spinner = submitBtn.querySelector('.spinner-border');
        const alertContainer = document.getElementById('alert-container');

        // Set CSRF token for all AJAX requests
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            // Show loading state
            submitBtn.disabled = true;
            spinner.classList.remove('d-none');

            // Clear previous alerts
            alertContainer.innerHTML = '';

            const formData = new FormData(form);

            try {
                const response = await fetch('{{ route("player.upgrade") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                });

                const data = await response.json();

                if (data.success) {
                    showAlert(data.message, 'success');
                    setTimeout(() => {
                        window.location.href = data.redirect_url || '{{ route("profile.show") }}';
                    }, 2000);
                } else {
                    if (data.errors) {
                        // Show validation errors
                        let errorMessage = data.message + '<ul>';
                        for (const field in data.errors) {
                            data.errors[field].forEach(error => {
                                errorMessage += `<li>${error}</li>`;
                            });
                        }
                        errorMessage += '</ul>';
                        showAlert(errorMessage, 'danger');
                    } else {
                        showAlert(data.message, 'danger');
                    }
                }

            } catch (error) {
                console.error('Upgrade error:', error);
                showAlert('{{ __('app.auth.connection_error') }}', 'danger');
            } finally {
                // Hide loading state
                submitBtn.disabled = false;
                spinner.classList.add('d-none');
            }
        });

        function showAlert(message, type) {
            const alert = document.createElement('div');
            alert.className = `alert alert-${type} alert-dismissible fade show`;
            alert.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            alertContainer.appendChild(alert);
        }
    });
</script>
@endpush
