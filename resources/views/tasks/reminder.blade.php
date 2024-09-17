<div class="reminders">
    <div class="reminder template flex items-center mb-3">
        <div class="flex-grow">
            <select id="reminder_preset" name="reminder" class="form-select required block w-full mt-1" required>
                <option value="">Select</option>
                <option value="5_minutes_before" {{ old('reminder_preset') == '5_minutes_before' ? 'selected' : '' }}>5
                    minutes before</option>
                <option value="10_minutes_before" {{ old('reminder_preset') == '10_minutes_before' ? 'selected' : '' }}>
                    10 minutes before</option>
                <option value="30_minutes_before" {{ old('reminder_preset') == '30_minutes_before' ? 'selected' : '' }}>
                    30 minutes before</option>
                <option value="1_hour_before" {{ old('reminder_preset') == '1_hour_before' ? 'selected' : '' }}>1 hour
                    before</option>
                <option value="1_day_before" {{ old('reminder_preset') == '1_day_before' ? 'selected' : '' }}>1 day
                    before</option>
            </select>
            {{-- <input type="datetime-local" id="reminder_custom" name="reminder_custom" class="form-control"
                min="{{ now()->format('Y-m-d\TH:i') }}"
                value="{{ old('reminder_custom', $task->reminder_custom ?? now()->format('Y-m-d\TH:i')) }}"> --}}
        </div>
        <div class="ml-3">
            <button type="button" class="bg-red-500 hover:bg-red-400 text-white font-bold py-2 px-4 rounded"
                id="remove_reminder" title="Remove" aria-label="Remove">
                <i class="uil uil-trash-alt"></i>
            </button>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#add_reminder').on('click', function() {
            const $reminder = $('.reminder.template').clone().removeClass('template hidden');
            $('.reminders').append($reminder);
            $reminder.show(300);
        });

        $(document).on('click', '#remove_reminder', function() {
            $(this).closest('.reminder').remove();
        });

        $('#reset').on('click', function(e) {
            e.preventDefault();
            $('.reminders .reminder').not('.template').remove();
        });

        $('form').on('submit', function() {
            $('.reminders .reminder').not('.template').each(function(index) {
                $(this).find('select').attr('name', `reminder[${index}]`);
            });
        });
    });
</script>
