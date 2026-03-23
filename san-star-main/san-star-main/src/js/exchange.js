document.addEventListener('DOMContentLoaded', () => {
    // Обработка FAQ аккордеона
    const faqItems = document.querySelectorAll('.faq__item');
    
    faqItems.forEach(item => {
        const question = item.querySelector('.faq__question');
        const answer = item.querySelector('.faq__answer');
        
        question.addEventListener('click', () => {
            const isActive = question.classList.contains('active');
            
            // Закрываем все остальные ответы
            document.querySelectorAll('.faq__question.active').forEach(activeQuestion => {
                if (activeQuestion !== question) {
                    activeQuestion.classList.remove('active');
                    activeQuestion.nextElementSibling.classList.remove('active');
                }
            });
            
            // Переключаем текущий ответ
            question.classList.toggle('active');
            answer.classList.toggle('active');
        });
    });

    // Обработка формы обмена и возврата
    const exchangeForm = document.getElementById('exchangeForm');
    const fileInputs = document.querySelectorAll('.form__file');

    // Обработка загрузки файлов
    fileInputs.forEach(input => {
        input.addEventListener('change', (e) => {
            const fileName = e.target.files[0]?.name;
            const label = e.target.parentElement;
            
            if (fileName) {
                const originalText = label.textContent.trim();
                label.innerHTML = `${originalText}<br><span class="form__file-name">${fileName}</span>`;
            }
        });
    });

    // Обработка отправки формы
    exchangeForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        // Собираем данные формы
        const formData = new FormData(exchangeForm);

        try {
            // Здесь будет отправка данных на сервер
            // const response = await fetch('/api/exchange', {
            //     method: 'POST',
            //     body: formData
            // });

            // if (response.ok) {
            //     const result = await response.json();
            //     showSuccessMessage('Ваша претензия успешно отправлена');
            //     exchangeForm.reset();
            // } else {
            //     throw new Error('Ошибка при отправке формы');
            // }

            // Временно просто показываем сообщение об успехе
            showSuccessMessage('Ваша претензия успешно отправлена');
            exchangeForm.reset();
            
            // Очищаем имена файлов
            fileInputs.forEach(input => {
                const label = input.parentElement;
                label.innerHTML = label.innerHTML.split('<br>')[0];
            });

        } catch (error) {
            showErrorMessage('Произошла ошибка при отправке формы. Пожалуйста, попробуйте позже.');
            console.error('Error:', error);
        }
    });
});

// Функция для показа сообщения об успехе
function showSuccessMessage(message) {
    Modal.open(`
        <div class="modal__success">
            <div class="modal__icon">✓</div>
            <h3 class="modal__title">Успешно!</h3>
            <p class="modal__message">${message}</p>
            <button class="button button--primary modal__button">OK</button>
        </div>
    `);
}

// Функция для показа сообщения об ошибке
function showErrorMessage(message) {
    Modal.open(`
        <div class="modal__error">
            <div class="modal__icon">✕</div>
            <h3 class="modal__title">Ошибка</h3>
            <p class="modal__message">${message}</p>
            <button class="button button--primary modal__button">OK</button>
        </div>
    `);
} 