import Swal from 'sweetalert2'

export const showError = (text = 'Ошибка', time = 3000) => {
	Swal.fire({
		text: text,
		toast: true,
		width: '20rem',
		showConfirmButton: false,
		showCloseButton: false,
		timer: time,
		timerProgressBar: true,
		showClass: {
			popup: '',
		},
		customClass: {
			'popup' : '_error',
			'htmlContainer' : '_error',
		},
		scrollbarPadding: false,
	})
}

export const showSuccess = (text = 'Успех', time = 3000) => {
	Swal.fire({
		text: text,
		toast: true,
		width: '20rem',
		showConfirmButton: false,
		showCloseButton: false,
		timer: time,
		timerProgressBar: true,
		showClass: {
			popup: '',
		},
		customClass: {
			'popup' : '_success',
			'htmlContainer' : '_success',
		},
		scrollbarPadding: false,
	})
}