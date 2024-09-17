<div class="rimenders">
    <div class="row reminder template" style="">
        <div class="col col-11 mb-3">
            <select id="reminder_preset" name="reminder" class="form-select required" required>
                <option value="">Select</option>
                <option value="5_minutes_before" {{ old('reminder_preset') == '5_minutes_before' ? 'selected' : '' }}>5
                    minutes before
                </option>
                <option value="10_minutes_before" {{ old('reminder_preset') == '10_minutes_before' ? 'selected' : '' }}>
                    10 minutes before
                </option>
                <option value="30_minutes_before" {{ old('reminder_preset') == '30_minutes_before' ? 'selected' : '' }}>
                    30 minutes before
                </option>
                <option value="1_hour_before" {{ old('reminder_preset') == '1_hour_before' ? 'selected' : '' }}>
                    1 hour before
                </option>
                <option value="1_day_before" {{ old('reminder_preset') == '1_day_before' ? 'selected' : '' }}>
                    1 day before
                </option>
            </select>
            {{-- <input type="datetime-local" id="reminder_custom" name="reminder_custom" class="form-control"
                min="{{ now()->format('Y-m-d\TH:i') }}"
                value="{{ old('reminder_custom', $task->reminder_custom ?? now()->format('Y-m-d\TH:i')) }}"> --}}
        </div>
        <div class="col col-1">
            <button type="button" class="btn btn-outline-danger" id="remove_reminder" title="Remove"
                aria-label="Remove">
                <i class="uil uil-trash-alt"></i>
            </button>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#add_reminder').on('click', function() {
            const $reminder = $('.reminder.template').clone().removeClass('template');
            $('.rimenders').append($reminder);
            $reminder.show(300);
        });

        $(document).on('click', '#remove_reminder', function() {
            $(this).closest('.reminder').remove();
        });

        $('#reset').on('click', function(e) {
            e.preventDefault();
            $('.rimenders .reminder').not('.template').remove();
        });

        $('form').on('submit', function() {
            $('.rimenders .reminder').not('.template').each(function(index) {
                $(this).find('select').attr('name', `reminder[${index}]`);
            });
        });
    });
</script>
