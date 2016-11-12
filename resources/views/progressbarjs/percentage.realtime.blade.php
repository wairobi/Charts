@php($min = count($model->values) >= 2 ? $model->values[1] : 0)
@php($max = count($model->values) >= 3 ? $model->values[2] : 100)

<center>@include('charts::_partials.container.div')</center><script type="text/javascript">
    $(function() {
        var {{ $model->id }} = new ProgressBar.Circle('#{{ $model->id }}', {
            @if($model->colors and count($model->colors) >= 2)
                color: {{ $model->colors[1] }},
            @else
                color: '#000',
            @endif
            // This has to be the same size as the maximum width to
            // prevent clipping
            strokeWidth: 4,
            trailWidth: 1,
            easing: 'easeInOut',
            duration: 1000,
            text: {
                autoStyleContainer: false
            },
            from: { color: '#aaa', width: 4 },
            to: { color: {{ $model->colors ? $model->colors[0] : '#333' }}, width: 4 },
            // Set default step function for all animate calls
            step: function(state, circle) {
                circle.path.setAttribute('stroke', state.color)
                circle.path.setAttribute('stroke-width', state.width)

                var value = "{{ $model->values[0] }}";
                @if(value === 0)
                    circle.setText('')
                @else
                    circle.setText(value)
                @endif
            }
        })

        {{ $model->id }}.animate({{ ($model->values[0] - $min) / ($max - $min) }})

        setInterval(function() {
            $.getJSON("{{ $model->url }}", function( jdata ) {
                var v = (jdata["{{ $model->value_name }}"] - $min)/($max - $min)
                {{ $model->id }}.animate(v, {
                    step: function(state, circle, attachment) {
                        circle.setText(jdata["{{ $model->value_name }}"].toString())
                    }
                })
            })
        }, {{ $model->interval }})
    });
</script>

