# Missing & Incomplete Features - ALERT+ Web Application

## 🔴 **CRITICAL / HIGH PRIORITY**

### 1. **Location Ping Functionality (Currently Simulated)**
   - **Status**: Placeholder/simulation only
   - **Location**: `dashboard.blade.php` line 917-938
   - **Issue**: The "Ping Location" button uses `setTimeout()` with random success/failure
   - **Needs**: Real API endpoint that:
     - Sends SMS/command to IoT device via GSM
     - Waits for device response with GPS coordinates
     - Updates location logs with real data
   - **Impact**: Core feature doesn't work

### 2. **Reverse Geocoding (Address Lookup)**
   - **Status**: Addresses are always `null` or "—"
   - **Location**: `IoTController@telemetry` line 170, `LocationLog` model
   - **Issue**: GPS coordinates stored but addresses never populated
   - **Needs**: 
     - Google Maps Geocoding API integration
     - Background job to reverse geocode coordinates → addresses
     - Update `location_logs.address` field
   - **Impact**: Users can't see readable addresses, only coordinates

### 3. **API Route Security (No CSRF Protection)**
   - **Status**: API routes have no middleware protection
   - **Location**: `routes/api.php`
   - **Issue**: IoT endpoints are public (only optional shared secret)
   - **Needs**: 
     - Rate limiting middleware
     - CORS configuration (if needed)
     - API throttling to prevent abuse
   - **Impact**: Vulnerable to DDoS and abuse

### 4. **Delete/Edit Dependent Functionality**
   - **Status**: Completely missing
   - **Location**: Dashboard UI
   - **Issue**: Can add dependents but cannot:
     - Edit dependent info (name, category, SIM number)
     - Delete dependents
     - Update device ID
   - **Needs**: 
     - Edit modal/form
     - Delete confirmation dialog
     - Backend routes: `PUT /dashboard/children/{id}`, `DELETE /dashboard/children/{id}`
   - **Impact**: Poor user experience, orphaned records

---

## 🟡 **MEDIUM PRIORITY**

### 5. **Real-time Updates (WebSocket/Polling)**
   - **Status**: Manual refresh only
   - **Location**: Dashboard
   - **Issue**: Dashboard doesn't auto-update when:
     - Device sends new telemetry
     - New location logs arrive
     - Presence calls are sent
   - **Needs**: 
     - WebSocket (Laravel Echo + Pusher) OR
     - Polling mechanism (setInterval)
   - **Impact**: Users must manually refresh to see updates

### 6. **Error Handling & Logging**
   - **Status**: Basic, no structured logging
   - **Location**: Controllers
   - **Issue**: 
     - No try-catch blocks in many controllers
     - No logging of IoT device errors
     - No error tracking/reporting
   - **Needs**: 
     - Structured logging (Monolog)
     - Error tracking (Sentry, Bugsnag)
     - User-friendly error pages
   - **Impact**: Hard to debug production issues

### 7. **Data Validation & Sanitization**
   - **Status**: Basic validation exists
   - **Location**: Controllers
   - **Issue**: 
     - No input sanitization for XSS
     - Device IDs not validated for format
     - SIM numbers not validated for format
   - **Needs**: 
     - Custom validation rules
     - Input sanitization middleware
     - SQL injection prevention (Laravel handles this, but verify)

### 8. **Address Field Population**
   - **Status**: Addresses stored as null
   - **Location**: `IoTController@telemetry`, `LocationLog` creation
   - **Issue**: When GPS coordinates arrive, address should be reverse geocoded
   - **Needs**: 
     - Queue job for reverse geocoding
     - Google Maps Geocoding API integration
     - Fallback if API fails
   - **Impact**: Location logs show "—" instead of readable addresses

### 9. **Presence Call Status Updates**
   - **Status**: Status always "sent" or "failed" (hardcoded)
   - **Location**: `DashboardController@sendPresenceCall` line 115
   - **Issue**: Status doesn't reflect actual device acknowledgment
   - **Needs**: 
     - Device should acknowledge when call is played
     - Update `presence_calls.status` based on device response
   - **Impact**: Can't tell if device actually received/played the call

### 10. **Device Status Monitoring**
   - **Status**: Basic (last_seen_at only)
   - **Location**: `Child` model
   - **Issue**: No alerts for:
     - Device offline for X minutes
     - Low battery warnings
     - Weak signal warnings
   - **Needs**: 
     - Background job to check device health
     - Notification system (email/in-app)
     - Alert thresholds configuration
   - **Impact**: Parents don't know if device is offline/low battery

---

## 🟢 **LOW PRIORITY / NICE TO HAVE**

### 11. **API Documentation**
   - **Status**: No documentation
   - **Location**: None
   - **Needs**: 
     - OpenAPI/Swagger documentation
     - Postman collection
     - API endpoint documentation for IoT devices
   - **Impact**: Hard for developers to integrate

### 12. **Unit/Feature Tests**
   - **Status**: Only example tests exist
   - **Location**: `tests/` directory
   - **Needs**: 
     - Tests for controllers
     - Tests for IoT endpoints
     - Tests for authentication
   - **Impact**: No confidence in code changes

### 13. **Dependent Management UI Improvements**
   - **Status**: Basic
   - **Needs**: 
     - Bulk operations (delete multiple)
     - Search/filter dependents
     - Sort by name/device/last seen
   - **Impact**: Hard to manage many dependents

### 14. **Location History Visualization**
   - **Status**: Basic map with single marker
   - **Needs**: 
     - Path/trail showing movement over time
     - Heatmap of frequent locations
     - Time-based animation
   - **Impact**: Limited location insights

### 15. **Export/Backup Enhancements**
   - **Status**: Basic JSON/CSV export
   - **Needs**: 
     - Scheduled automatic backups
     - Email backup delivery
     - Import from backup (partial exists)
   - **Impact**: Manual backup process

### 16. **Settings Page Functionality**
   - **Status**: UI exists but not functional
   - **Location**: Settings page
   - **Issue**: 
     - "Secondary Parent" field doesn't save
     - "Device Defaults" don't save
     - "Data Retention" doesn't work
   - **Needs**: 
     - Backend routes for settings
     - Database table for user settings
     - Settings persistence
   - **Impact**: Settings page is decorative only

### 17. **Password Reset Functionality**
   - **Status**: Missing
   - **Location**: Login page
   - **Needs**: 
     - "Forgot Password" link
     - Password reset email
     - Reset token system
   - **Impact**: Users locked out if they forget password

### 18. **Email Notifications**
   - **Status**: No email system
   - **Needs**: 
     - Device offline alerts
     - Low battery alerts
     - Location alerts (geofencing)
     - Daily/weekly summaries
   - **Impact**: No proactive alerts

### 19. **Geofencing**
   - **Status**: Not implemented
   - **Needs**: 
     - Define safe zones
     - Alert when device leaves/enters zone
     - Multiple zones per dependent
   - **Impact**: No location-based alerts

### 20. **Device Registration Flow**
   - **Status**: Manual entry only
   - **Needs**: 
     - QR code generation for device pairing
     - Device activation flow
     - Device verification
   - **Impact**: Manual, error-prone setup

### 21. **Multi-language Support**
   - **Status**: English only
   - **Needs**: 
     - Translation files
     - Language switcher
   - **Impact**: Limited accessibility

### 22. **Mobile Responsiveness Improvements**
   - **Status**: Basic responsive design
   - **Needs**: 
     - Better mobile navigation
     - Touch-optimized controls
     - Mobile-specific features
   - **Impact**: Suboptimal mobile experience

### 23. **Activity Logging/Audit Trail**
   - **Status**: No audit trail
   - **Needs**: 
     - Log all user actions
     - Track who did what and when
     - Security audit log
   - **Impact**: No accountability/tracking

### 24. **Data Retention Policy**
   - **Status**: UI exists but not functional
   - **Needs**: 
     - Background job to delete old logs
     - Configurable retention period
     - Archive old data before deletion
   - **Impact**: Database will grow indefinitely

### 25. **IoT Alert Model & Management**
   - **Status**: Table exists but no UI
   - **Location**: `iot_alerts` table
   - **Needs**: 
     - View alerts in dashboard
     - Alert filtering/search
     - Alert types management
   - **Impact**: Alerts are stored but invisible

---

## 🔧 **TECHNICAL DEBT**

### 26. **Code Organization**
   - Large JavaScript in Blade template (should be separate files)
   - No service classes for business logic
   - Controllers doing too much

### 27. **Database Indexing**
   - Missing indexes on frequently queried columns:
     - `children.device_id` (has unique constraint, but verify index)
     - `location_logs.child_id`, `location_logs.timestamp`
     - `presence_calls.child_id`, `presence_calls.timestamp`

### 28. **Caching**
   - No caching for:
     - User profile data
     - Children list
     - Location logs (for performance)

### 29. **Queue Jobs**
   - Reverse geocoding should be queued (not blocking)
   - Email sending should be queued
   - Long-running tasks should be async

### 30. **Environment Configuration**
   - Missing `.env.example` with all required variables
   - No documentation on required environment variables

---

## 📋 **SUMMARY BY PRIORITY**

### **Must Fix Before Production:**
1. Location Ping (real implementation)
2. Reverse Geocoding
3. API Security (rate limiting)
4. Delete/Edit Dependents

### **Should Fix Soon:**
5. Real-time Updates
6. Error Handling
7. Address Population
8. Device Status Monitoring

### **Can Wait:**
9-30. All other items

---

## 🎯 **RECOMMENDED IMPLEMENTATION ORDER**

1. **Week 1**: Fix critical issues (#1-4)
2. **Week 2**: Implement medium priority (#5-10)
3. **Week 3+**: Address low priority items as needed

