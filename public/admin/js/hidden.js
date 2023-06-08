$(document).ready(
    function () {
        $('.home-page-show').click(function (e) {
            const bannerId = $(this).attr('banner-id');

            const main = document.querySelector('.content-' + bannerId);

            main.getElementsByClassName('card-body')[0].style.display = 'block';
            main.getElementsByClassName('home-page-show')[0].classList.add('hidden');
            main.getElementsByClassName('home-page-hide')[0].classList.remove('hidden');
            if (main.getElementsByClassName(`add-${bannerId}`)[0]) {
                main.getElementsByClassName(`add-${bannerId}`)[0].style.display = 'block';
            }
        });

        $('.home-page-hide').click(function (e) {
            const bannerId = $(this).attr('banner-id');

            const main = document.querySelector(`.content-${bannerId}`);

            main.getElementsByClassName('card-body')[0].style.display = 'none'
            main.getElementsByClassName('home-page-show')[0].classList.remove('hidden');
            main.getElementsByClassName('home-page-hide')[0].classList.add('hidden');
            if (main.getElementsByClassName(`add-${bannerId}`)[0]) {
                main.getElementsByClassName(`add-${bannerId}`)[0].style.display = 'none'
            }
        });
    }
)


