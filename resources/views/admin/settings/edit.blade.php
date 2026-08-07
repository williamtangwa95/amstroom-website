@extends('layouts.admin')

@section('title', 'System Settings')

@section('content')
    <div class="admin-header" style="justify-content: flex-start; gap: 20px;">
        <a href="{{ route('admin.dashboard') }}" class="btn-action btn-back"><i class="fas fa-arrow-left"></i> Dashboard</a>
        <h1>Branding & System Settings</h1>
    </div>

    <div class="admin-form">
        <div class="form-card">
            @if($errors->any())
                <div class="error-box" style="background: rgba(220, 53, 69, 0.1); border-left: 4px solid var(--danger); padding: 15px; border-radius: 6px; margin-bottom: 20px; color: var(--danger);">
                    <ul style="list-style: none; margin: 0; padding: 0;">
                        @foreach($errors->all() as $error)
                            <li><i class="fas fa-exclamation-circle"></i> {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Tabs Navigation -->
            <div class="tabs-navigation" style="display: flex; border-bottom: 2px solid #e2e8f0; margin-bottom: 30px; gap: 15px; overflow-x: auto; padding-bottom: 2px;">
                <button type="button" class="tab-btn active" data-tab="tab-branding" style="padding: 12px 20px; font-weight: 600; font-size: 14.5px; color: #64748b; border: none; background: none; cursor: pointer; border-bottom: 3px solid transparent; transition: all 0.3s; white-space: nowrap; display: flex; align-items: center; gap: 8px; font-family: inherit;">
                    <i class="fas fa-building"></i> Branding
                </button>
                <button type="button" class="tab-btn" data-tab="tab-contact" style="padding: 12px 20px; font-weight: 600; font-size: 14.5px; color: #64748b; border: none; background: none; cursor: pointer; border-bottom: 3px solid transparent; transition: all 0.3s; white-space: nowrap; display: flex; align-items: center; gap: 8px; font-family: inherit;">
                    <i class="fas fa-address-book"></i> Contact Details
                </button>
                <button type="button" class="tab-btn" data-tab="tab-map" style="padding: 12px 20px; font-weight: 600; font-size: 14.5px; color: #64748b; border: none; background: none; cursor: pointer; border-bottom: 3px solid transparent; transition: all 0.3s; white-space: nowrap; display: flex; align-items: center; gap: 8px; font-family: inherit;">
                    <i class="fas fa-map-marked-alt"></i> Google Map
                </button>
                <button type="button" class="tab-btn" data-tab="tab-socials" style="padding: 12px 20px; font-weight: 600; font-size: 14.5px; color: #64748b; border: none; background: none; cursor: pointer; border-bottom: 3px solid transparent; transition: all 0.3s; white-space: nowrap; display: flex; align-items: center; gap: 8px; font-family: inherit;">
                    <i class="fas fa-share-alt"></i> Social Media
                </button>
            </div>

            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- TAB 1: Branding -->
                <div id="tab-branding" class="tab-content active">
                    <div class="form-group">
                        <label for="office_name">Company / Office Name *</label>
                        <input type="text" name="office_name" id="office_name" class="form-control" value="{{ old('office_name', $officeName) }}" required>
                        <small style="color: #64748b; font-size: 12px; margin-top: 5px; display: block;">
                            The primary brand name displayed across layouts, headers, page titles, and copyright elements.
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="slogan">Company Slogan *</label>
                        <input type="text" name="slogan" id="slogan" class="form-control" value="{{ old('slogan', $slogan) }}" required>
                        <small style="color: #64748b; font-size: 12px; margin-top: 5px; display: block;">
                            The branding subtitle or slogan displayed under the main company name.
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="logo_file">Upload New Logo (Image file)</label>
                        <input type="file" name="logo_file" id="logo_file" class="form-control" accept="image/*" style="padding: 8px 12px;">
                        <small style="color: #64748b; font-size: 12px; margin-top: 5px; display: block;">
                            Upload a clear image file (PNG, JPG, SVG, ICO). It will automatically update the browser tab icon and layout header branding.
                        </small>
                        
                        <!-- Logo Preview -->
                        <div style="margin-top: 15px; display: flex; align-items: center; gap: 15px; padding: 15px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px;">
                            <img id="logo-preview" src="{{ asset($logoPath) }}" alt="Logo Preview" style="width: 80px; height: 80px; object-fit: contain; padding: 5px; border: 1px dashed #cbd5e1; border-radius: 8px; background: #fff;">
                            <div>
                                <span style="font-size: 13px; color: #475569; font-weight: 600; display: block; margin-bottom: 2px;">Logo Preview</span>
                                <span style="font-size: 11px; color: #64748b; font-family: monospace; word-break: break-all;">{{ $logoPath }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="slider_interval">Hero Slider Auto-Play Interval (Seconds) *</label>
                        <input type="number" name="slider_interval" id="slider_interval" class="form-control" value="{{ old('slider_interval', $sliderInterval) }}" min="1" max="60" required>
                        <small style="color: #64748b; font-size: 12px; margin-top: 5px; display: block;">
                            The duration (in seconds) that each slide is shown before moving to the next one automatically.
                        </small>
                    </div>
                </div>

                <!-- TAB 2: Contact Details -->
                <div id="tab-contact" class="tab-content" style="display: none;">
                    <div class="form-group">
                        <label for="contact_address">Store / Office Address *</label>
                        <textarea name="contact_address" id="contact_address" class="form-control" rows="3" style="resize: vertical; min-height: 80px;" required>{{ old('contact_address', $contactAddress) }}</textarea>
                        <small style="color: #64748b; font-size: 12px; margin-top: 5px; display: block;">
                            The physical address of the store (accepts line breaks).
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="contact_phone">Phone / Call Number *</label>
                        <input type="text" name="contact_phone" id="contact_phone" class="form-control" value="{{ old('contact_phone', $contactPhone) }}" required>
                        <small style="color: #64748b; font-size: 12px; margin-top: 5px; display: block;">
                            The primary phone number shown for voice calls.
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="contact_whatsapp">WhatsApp Chat Number / Link *</label>
                        <input type="text" name="contact_whatsapp" id="contact_whatsapp" class="form-control" value="{{ old('contact_whatsapp', $contactWhatsapp) }}" required>
                        <small style="color: #64748b; font-size: 12px; margin-top: 5px; display: block;">
                            The WhatsApp number or direct link (e.g. +255 710 635 173 or 255710635173).
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="contact_email">Email Address *</label>
                        <input type="email" name="contact_email" id="contact_email" class="form-control" value="{{ old('contact_email', $contactEmail) }}" required>
                        <small style="color: #64748b; font-size: 12px; margin-top: 5px; display: block;">
                            The primary contact email address.
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="contact_hours">Business Hours *</label>
                        <textarea name="contact_hours" id="contact_hours" class="form-control" rows="2" style="resize: vertical; min-height: 60px;" required>{{ old('contact_hours', $contactHours) }}</textarea>
                        <small style="color: #64748b; font-size: 12px; margin-top: 5px; display: block;">
                            Opening hours (accepts line breaks).
                        </small>
                    </div>
                </div>

                <!-- TAB 3: Google Map -->
                <div id="tab-map" class="tab-content" style="display: none;">
                    <div class="form-group">
                        <label for="google_map_iframe">Google Map Embed Code or URL</label>
                        <textarea name="google_map_iframe" id="google_map_iframe" class="form-control" rows="6" style="resize: vertical; font-family: monospace; font-size: 13px;" placeholder="https://maps.google.com/maps?q=... or <iframe src='...'></iframe>">{{ old('google_map_iframe', $googleMapIframe) }}</textarea>
                        <small style="color: #64748b; font-size: 12px; margin-top: 5px; display: block;">
                            Paste a Google Maps iframe tag (from Google Maps -> Share -> Embed map) or a direct embed URL.
                        </small>
                    </div>

                    <!-- Map Preview -->
                    <div style="margin-top: 20px;">
                        <label style="display: block; font-weight: 500; margin-bottom: 8px; font-size: 14px; color: var(--dark);">Map Preview</label>
                        <div id="map-preview-container" style="width: 100%; height: 250px; background: #e2e8f0; border-radius: 10px; overflow: hidden; display: flex; align-items: center; justify-content: center; border: 1px solid #cbd5e1; box-shadow: inset 0 2px 4px rgba(0,0,0,0.06);">
                            @php
                                $isIframe = str_contains($googleMapIframe, '<iframe');
                            @endphp
                            @if($googleMapIframe)
                                @if($isIframe)
                                    {!! $googleMapIframe !!}
                                @else
                                    <iframe src="{{ $googleMapIframe }}" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                                @endif
                            @else
                                <span style="color: #64748b; font-size: 14px;"><i class="fas fa-map-marked-alt" style="font-size: 24px; display: block; margin: 0 auto 10px; text-align: center;"></i> No Map Configured</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- TAB 4: Social Media -->
                <div id="tab-socials" class="tab-content" style="display: none;">
                    <p style="color: #64748b; font-size: 13px; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px;">
                        Provide the URL and text handle for each platform. Social platform icons will only show up on the front page if their URL is filled out.
                    </p>

                    <!-- Instagram -->
                    <div style="border-bottom: 1px solid #e2e8f0; padding-bottom: 20px; margin-bottom: 20px;">
                        <h4 style="margin-bottom: 15px; color: var(--primary); display: flex; align-items: center; gap: 8px;"><i class="fab fa-instagram" style="font-size: 18px;"></i> Instagram</h4>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                            <div>
                                <label for="social_instagram" style="font-size: 13px; font-weight: 500; color: #475569;">Instagram URL</label>
                                <input type="url" name="social_instagram" id="social_instagram" class="form-control" placeholder="https://instagram.com/your_handle" value="{{ old('social_instagram', $socialInstagram) }}">
                            </div>
                            <div>
                                <label for="social_instagram_handle" style="font-size: 13px; font-weight: 500; color: #475569;">Display Handle / Text</label>
                                <input type="text" name="social_instagram_handle" id="social_instagram_handle" class="form-control" placeholder="@username" value="{{ old('social_instagram_handle', $socialInstagramHandle) }}">
                            </div>
                        </div>
                    </div>

                    <!-- Facebook -->
                    <div style="border-bottom: 1px solid #e2e8f0; padding-bottom: 20px; margin-bottom: 20px;">
                        <h4 style="margin-bottom: 15px; color: var(--primary); display: flex; align-items: center; gap: 8px;"><i class="fab fa-facebook-f" style="font-size: 18px;"></i> Facebook</h4>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                            <div>
                                <label for="social_facebook" style="font-size: 13px; font-weight: 500; color: #475569;">Facebook URL</label>
                                <input type="url" name="social_facebook" id="social_facebook" class="form-control" placeholder="https://facebook.com/page" value="{{ old('social_facebook', $socialFacebook) }}">
                            </div>
                            <div>
                                <label for="social_facebook_handle" style="font-size: 13px; font-weight: 500; color: #475569;">Display Handle / Text</label>
                                <input type="text" name="social_facebook_handle" id="social_facebook_handle" class="form-control" placeholder="Page Name" value="{{ old('social_facebook_handle', $socialFacebookHandle) }}">
                            </div>
                        </div>
                    </div>

                    <!-- TikTok -->
                    <div style="border-bottom: 1px solid #e2e8f0; padding-bottom: 20px; margin-bottom: 20px;">
                        <h4 style="margin-bottom: 15px; color: var(--primary); display: flex; align-items: center; gap: 8px;"><i class="fab fa-tiktok" style="font-size: 18px;"></i> TikTok</h4>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                            <div>
                                <label for="social_tiktok" style="font-size: 13px; font-weight: 500; color: #475569;">TikTok URL</label>
                                <input type="url" name="social_tiktok" id="social_tiktok" class="form-control" placeholder="https://tiktok.com/@username" value="{{ old('social_tiktok', $socialTiktok) }}">
                            </div>
                            <div>
                                <label for="social_tiktok_handle" style="font-size: 13px; font-weight: 500; color: #475569;">Display Handle / Text</label>
                                <input type="text" name="social_tiktok_handle" id="social_tiktok_handle" class="form-control" placeholder="Username" value="{{ old('social_tiktok_handle', $socialTiktokHandle) }}">
                            </div>
                        </div>
                    </div>

                    <!-- Twitter/X -->
                    <div style="border-bottom: 1px solid #e2e8f0; padding-bottom: 20px; margin-bottom: 20px;">
                        <h4 style="margin-bottom: 15px; color: var(--primary); display: flex; align-items: center; gap: 8px;"><i class="fab fa-x-twitter" style="font-size: 18px;"></i> Twitter / X</h4>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                            <div>
                                <label for="social_twitter" style="font-size: 13px; font-weight: 500; color: #475569;">Twitter / X URL</label>
                                <input type="url" name="social_twitter" id="social_twitter" class="form-control" placeholder="https://x.com/username" value="{{ old('social_twitter', $socialTwitter) }}">
                            </div>
                            <div>
                                <label for="social_twitter_handle" style="font-size: 13px; font-weight: 500; color: #475569;">Display Handle / Text</label>
                                <input type="text" name="social_twitter_handle" id="social_twitter_handle" class="form-control" placeholder="@username" value="{{ old('social_twitter_handle', $socialTwitterHandle) }}">
                            </div>
                        </div>
                    </div>

                    <!-- LinkedIn -->
                    <div style="border-bottom: 1px solid #e2e8f0; padding-bottom: 20px; margin-bottom: 20px;">
                        <h4 style="margin-bottom: 15px; color: var(--primary); display: flex; align-items: center; gap: 8px;"><i class="fab fa-linkedin-in" style="font-size: 18px;"></i> LinkedIn</h4>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                            <div>
                                <label for="social_linkedin" style="font-size: 13px; font-weight: 500; color: #475569;">LinkedIn URL</label>
                                <input type="url" name="social_linkedin" id="social_linkedin" class="form-control" placeholder="https://linkedin.com/company/page" value="{{ old('social_linkedin', $socialLinkedin) }}">
                            </div>
                            <div>
                                <label for="social_linkedin_handle" style="font-size: 13px; font-weight: 500; color: #475569;">Display Handle / Text</label>
                                <input type="text" name="social_linkedin_handle" id="social_linkedin_handle" class="form-control" placeholder="Company Name" value="{{ old('social_linkedin_handle', $socialLinkedinHandle) }}">
                            </div>
                        </div>
                    </div>

                    <!-- YouTube -->
                    <div style="padding-bottom: 10px;">
                        <h4 style="margin-bottom: 15px; color: var(--primary); display: flex; align-items: center; gap: 8px;"><i class="fab fa-youtube" style="font-size: 18px;"></i> YouTube</h4>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                            <div>
                                <label for="social_youtube" style="font-size: 13px; font-weight: 500; color: #475569;">YouTube URL</label>
                                <input type="url" name="social_youtube" id="social_youtube" class="form-control" placeholder="https://youtube.com/channel" value="{{ old('social_youtube', $socialYoutube) }}">
                            </div>
                            <div>
                                <label for="social_youtube_handle" style="font-size: 13px; font-weight: 500; color: #475569;">Display Handle / Text</label>
                                <input type="text" name="social_youtube_handle" id="social_youtube_handle" class="form-control" placeholder="Channel Name" value="{{ old('social_youtube_handle', $socialYoutubeHandle) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions" style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #e2e8f0;">
                    <button type="submit" class="btn-action btn-primary"><i class="fas fa-save"></i> Save Settings</button>
                    <a href="{{ route('admin.dashboard') }}" class="btn-action btn-back">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <style>
        #map-preview-container iframe {
            width: 100% !important;
            height: 100% !important;
            border: none !important;
        }
    </style>
    <script>
        document.getElementById('logo_file').addEventListener('change', function(e) {
            const reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById('logo-preview').src = event.target.result;
            };
            if(e.target.files[0]) {
                reader.readAsDataURL(e.target.files[0]);
            }
        });

        // Tabs switching logic
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active classes from all buttons
                document.querySelectorAll('.tab-btn').forEach(b => {
                    b.classList.remove('active');
                    b.style.borderBottomColor = 'transparent';
                    b.style.color = '#64748b';
                });
                
                // Hide all contents
                document.querySelectorAll('.tab-content').forEach(c => c.style.display = 'none');

                // Add active state to clicked button
                this.classList.add('active');
                this.style.borderBottomColor = 'var(--primary)';
                this.style.color = 'var(--primary)';
                
                // Show corresponding content
                const tabId = this.getAttribute('data-tab');
                document.getElementById(tabId).style.display = 'block';
            });
        });

        // Set default style for active button on load
        const activeBtn = document.querySelector('.tab-btn.active');
        if (activeBtn) {
            activeBtn.style.borderBottomColor = 'var(--primary)';
            activeBtn.style.color = 'var(--primary)';
        }
    </script>
@endsection
