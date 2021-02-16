@extends('admin.layout')

@section('content')

<!-- Hero -->
<div class="bg-image" style="background-image: url('{{ asset('img/bg_admin.jpg') }}');">
    <div class="bg-black-10">
        <div class="content content-full content-top">
            <div class="pt-5 pb-4 text-center">
                <h1 class="h2 font-w700 mb-2 text-white">
                    Database Management
                </h1>
                <h2 class="h5 text-white-75 mb-0">
                    iRoof DB Backup / Restore Page
                </h2>
            </div>
        </div>
    </div>
</div>

<!-- Backup Page Content -->
<div class="content" style="text-align:left">
    <div class="row">
        <div class="col-md-3">
            <h2 class="content-heading pt-0">Backup Settings</h2>
            <div class="form-group">
                <div class="custom-control custom-radio custom-control-dark mb-1">
                    <input type="radio" class="custom-control-input" id="backup-everyday" name="backup-frequency" onchange="updateSetting()" <?php echo in_array("-1", $setting) ? 'checked' : ''; ?>>
                    <label class="custom-control-label" for="backup-everyday">Every day</label>
                </div>
                <div class="custom-control custom-radio custom-control-dark mb-1">
                    <input type="radio" class="custom-control-input" id="backup-weekly" name="backup-frequency" onchange="updateSetting()" <?php echo in_array("-1", $setting) ? '' : 'checked'; ?>>
                    <label class="custom-control-label" for="backup-weekly">Weekly</label>
                </div>
                <div class="ml-4 <?php echo in_array("-1", $setting) ? 'disabledPane' : ''; ?>" id="weekDays">
                    <div class="custom-control custom-checkbox custom-control-primary mb-1">
                        <input type="checkbox" class="custom-control-input" id="weekday-monday" name="weekday-monday" onchange="updateSetting()" <?php echo in_array("0", $setting) ? 'checked' : ''; ?>>
                        <label class="custom-control-label" for="weekday-monday">Monday</label>
                    </div>
                    <div class="custom-control custom-checkbox custom-control-success mb-1">
                        <input type="checkbox" class="custom-control-input" id="weekday-tuesday" name="weekday-tuesday" onchange="updateSetting()" <?php echo in_array("1", $setting) ? 'checked' : ''; ?>>
                        <label class="custom-control-label" for="weekday-tuesday">Tuesday</label>
                    </div>
                    <div class="custom-control custom-checkbox custom-control-info mb-1">
                        <input type="checkbox" class="custom-control-input" id="weekday-wednesday" name="weekday-wednesday" onchange="updateSetting()" <?php echo in_array("2", $setting) ? 'checked' : ''; ?>>
                        <label class="custom-control-label" for="weekday-wednesday">Wednesday</label>
                    </div>
                    <div class="custom-control custom-checkbox custom-control-warning mb-1">
                        <input type="checkbox" class="custom-control-input" id="weekday-thursday" name="weekday-thursday" onchange="updateSetting()" <?php echo in_array("3", $setting) ? 'checked' : ''; ?>>
                        <label class="custom-control-label" for="weekday-thursday">Thursday</label>
                    </div>
                    <div class="custom-control custom-checkbox custom-control-danger mb-1">
                        <input type="checkbox" class="custom-control-input" id="weekday-friday" name="weekday-friday" onchange="updateSetting()" <?php echo in_array("4", $setting) ? 'checked' : ''; ?>>
                        <label class="custom-control-label" for="weekday-friday">Friday</label>
                    </div>
                    <div class="custom-control custom-checkbox custom-control-light mb-1">
                        <input type="checkbox" class="custom-control-input" id="weekday-saturday" name="weekday-saturday" onchange="updateSetting()" <?php echo in_array("5", $setting) ? 'checked' : ''; ?>>
                        <label class="custom-control-label" for="weekday-saturday">Saturday</label>
                    </div>
                    <div class="custom-control custom-checkbox custom-control-dark mb-1">
                        <input type="checkbox" class="custom-control-input" id="weekday-sunday" name="weekday-sunday" onchange="updateSetting()" <?php echo in_array("6", $setting) ? 'checked' : ''; ?>>
                        <label class="custom-control-label" for="weekday-sunday">Sunday</label>
                    </div>
                </div>
            </div>

            <h2 class="content-heading pt-0">Backup Manually</h2>
            <div class="form-group">
                <button type="button" class="btn btn-hero-primary" onclick="backupNow()">Backup Now</button>
            </div>
        </div>
    </div>
</div>

@include('admin.backupdb.script')
@endsection