import "./bootstrap";

import Echo from "laravel-echo";

window.Echo = new Echo({
    broadcaster: "reverb",
    host: window.location.hostname + ":6001",
});

window.Echo.channel("calls").listen(".incoming-call", (data) => {
    Livewire.emit("incomingCallReceived", data.call);
});
