import {toast} from "react-toastify";

export const ErrorMessage = (e) => {
    if (e.response.status === 400) {
        e.response.data['#'].map((item) => {
            if (item === 'Konto zbanowane') {
                toast.error(item, {
                    position: toast.POSITION.BOTTOM_RIGHT
                });
                localStorage.removeItem('token');
            } else {
                toast.error(item, {
                    position: toast.POSITION.BOTTOM_RIGHT
                });
            }
        });
    } else if (e.response.data) {
        if (e.response.data.error_description === 'User account is disabled.') {
            toast.error('Potwierdz konto', {
                position: toast.POSITION.BOTTOM_RIGHT
            });
        }
    } else {
        console.log(e.response.data);
        toast.error('Coś poszło nie tak', {
            position: toast.POSITION.BOTTOM_RIGHT
        });
    }
};