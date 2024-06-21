<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signal Lights</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">

</head>

<body>
    <div class="container text-center">
        <h1>Signal Lights</h1>
        <div id="signals" class="d-flex justify-content-center">
            <div class="signal" id="signalA">
                <div class="signal-label">A</div>
            </div>
            <div class="signal" id="signalB">
                <div class="signal-label">B</div>
            </div>
            <div class="signal" id="signalC">
                <div class="signal-label">C</div>
            </div>
            <div class="signal" id="signalD">
                <div class="signal-label">D</div>
            </div>
        </div>
        <form id="signal-form" class="mt-4">
            <div class="form-group">
                <label for="sequence">Sequence (Enter numbers 1-4 for A-D in order):</label>
                <div class="d-flex justify-content-center">
                    <input type="text" id="A" name="sequence[]" class="form-control mx-1" required
                        style="width: 50px;">
                    <input type="text" id="B" name="sequence[]" class="form-control mx-1" required
                        style="width: 50px;">
                    <input type="text" id="C" name="sequence[]" class="form-control mx-1" required
                        style="width: 50px;">
                    <input type="text" id="D" name="sequence[]" class="form-control mx-1" required
                        style="width: 50px;">
                </div>
                <div class="error" id="sequenceError"></div>
            </div>
            <div class="form-group row justify-content-center">
                <label for="greenInterval" class="col-form-label text-right">Green Interval (seconds):</label>
                <div class="col-auto">
                    <input type="number" id="greenInterval" name="greenInterval" class="form-control" required
                        style="width: 100px;">
                </div>
                <div class="error" id="greenIntervalError"></div>
            </div>
            <div class="form-group row justify-content-center">
                <label for="yellowInterval" class="col-form-label text-right">Yellow Interval (seconds):</label>
                <div class="col-auto">
                    <input type="number" id="yellowInterval" name="yellowInterval" class="form-control" required
                        style="width: 100px;">
                </div>
                <div class="error" id="yellowIntervalError"></div>
            </div>
            <button type="button" id="start" class="btn btn-primary">Start</button>
            <button type="button" id="stop" class="btn btn-danger">Stop</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


    <script>
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            let changeSignalTimeout;

            $('#start').click(function() {
                $('.error').text('');
                let data = {
                    sequence: [$('#A').val(), $('#B').val(), $('#C').val(), $('#D').val()],
                    greenInterval: $('#greenInterval').val(),
                    yellowInterval: $('#yellowInterval').val()
                };

                $.ajax({
                    type: 'POST',
                    url: '{{ route('start') }}',
                    data: data,
                    success: function(response) {
                        startSignals(data.sequence, data.greenInterval, data.yellowInterval);
                    },
                    error: function(response) {
                        if (response.status === 422) {
                            let errors = response.responseJSON.errors;

                            if (errors['sequence.0']) {
                                $('#sequenceError').text(errors['sequence.0'][0]);
                            }
                            if (errors['sequence.1']) {
                                $('#sequenceError').text(errors['sequence.1'][0]);
                            }
                            if (errors['sequence.2']) {
                                $('#sequenceError').text(errors['sequence.2'][0]);
                            }
                            if (errors['sequence.3']) {
                                $('#sequenceError').text(errors['sequence.3'][0]);
                            }
                            if (errors.greenInterval) {
                                $('#greenIntervalError').text(errors.greenInterval.join(', '));
                            }
                            if (errors.yellowInterval) {
                                $('#yellowIntervalError').text(errors.yellowInterval.join(
                                    ', '));
                            }
                        } else {
                            alert('Error: ' + response.responseText);
                        }
                    }
                });
            });

            function startSignals(sequence, greenInterval, yellowInterval) {
                let index = 0;
                console.log(sequence);

                function changeSignal() {
                    if (index >= sequence.length) {
                        index = 0; // Restart the sequence if the end is reached
                    }

                    let currentSignal = sequence[index];
                    let signalElement = $('#signal' + getSignalLabel(currentSignal));

                    // Reset all signals to red
                    $('.signal').css('background', 'red');

                    // Change the current signal to green
                    signalElement.css('background', 'green');

                    // Set a timeout for the green interval
                    changeSignalTimeout = setTimeout(() => {
                        // Change the current signal to yellow
                        signalElement.css('background', 'yellow');

                        // Set a timeout for the yellow interval
                        changeSignalTimeout = setTimeout(() => {
                            // Move to the next signal in the sequence
                            index++;
                            changeSignal();
                        }, yellowInterval * 1000);
                    }, greenInterval * 1000);
                }

                changeSignal();
            }

            function getSignalLabel(number) {
                switch (number) {
                    case '1':
                        return 'A';
                    case '2':
                        return 'B';
                    case '3':
                        return 'C';
                    case '4':
                        return 'D';
                }
            }

            $('#stop').click(function() {
                clearTimeout(changeSignalTimeout);
                $('.signal').css('background', 'red');
                $('#yellowInterval').val('');
                $('#greenInterval').val('');
                $('#A').val('');
                $('#B').val('');
                $('#C').val('');
                $('#D').val('');
            });
        });
    </script>
</body>

</html>
