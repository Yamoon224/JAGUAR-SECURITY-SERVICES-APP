var notify = (msg, title='success') => {
    Lobibox.notify(title, {
        pauseDelayOnHover: true,
        continueDelayOnInactiveTab: false,
        position: 'top right',
        icon: 'bx bx-check-circle',
        msg: msg
    });
}