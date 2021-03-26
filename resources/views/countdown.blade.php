<x-guest-layout>

    <!-- background image -->
    <div class="z-0 fixed inset-0">
        <img class="object-cover w-full h-full" src="/images/cover-{{ $coverImage }}">
    </div>

    @if ($isMeetingToday)
        <!-- countdown -->
        <div x-cloak
             class="z-10 absolute inset-0 flex justify-center items-center"
             x-data="{
                isVisible: false,
                startTime: '{{ $startTime->toDateTimeLocalString() }}',
                countdown: 0,
                getDifferenceInMinutes: function(startTime) {
                    var now = new Date();
                    var meeting = new Date(startTime);

                    var diff = (meeting.getTime() - now.getTime()) / 1000;
                    diff /= 60;

                    return Math.ceil(diff);
                }
             }"
             x-init="setInterval(() => {
                countdown = getDifferenceInMinutes(startTime);
                if (countdown > 0 && countdown <= 30)
                    isVisible = true;
                if (countdown <= 0 || countdown > 30)
                    isVisible = false;
             }, 1000)"
             x-show="isVisible"
             x-transition:enter="transition ease-out duration-3500"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-3500"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
        >
            <!-- counter -->
            <div class="tracking-tight text-gray-800 antialiased pb-32">
                <span class="font-bold text-splash tracking-tighter" x-text="countdown"></span>
                <span class="font-black text-5xl">min</span>
            </div>
        </div>
    @endif

    <!-- footer -->
    <footer class="z-10 fixed bottom-0 leading-tight p-8 antialiased"
            x-data="{
                currentTime: '00:00',
                getCurrentTime: function() {
                    var today = new Date();
                    var hours = today.getHours();
                    hours = hours < 10 ? '0' + hours : hours;
                    var minutes = today.getMinutes();
                    minutes = minutes < 10 ? '0' + minutes : minutes;
                    var clock = hours + ':' + minutes;

                    return clock;
                }
            }"
            x-init="setInterval(() => {
                currentTime = getCurrentTime();
            }, 1000)"
    >
        <!-- current time -->
        <div class="bg-gray-800 inline-block text-center font-sans text-indigo-100 py-1 px-3 text-2xl rounded mb-4" x-text="currentTime + ' Uhr'"></div>
        <div class="font-black text-4xl text-gray-800">{{ $meetingName }}</div>
        <div class="text-3xl text-gray-600">Versammlung Neuwied</div>
    </footer>

</x-guest-layout>
