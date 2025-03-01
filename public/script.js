let originalFormHTML = document.querySelector('.purchase-section').innerHTML;

function initForm() {
  // Получаем элементы формы
  const starsInput = document.getElementById('starsAmount');
  const purchaseBtn = document.querySelector('.purchase-btn');
  const recipientInput = document.getElementById('recipient');

  // Обработчик для обновления текста кнопки покупки
  starsInput.addEventListener('input', () => {
    const value = starsInput.value.trim();
    if (value && !isNaN(value)) {
      purchaseBtn.textContent = `Купить ${value} Telegram Stars`;
    } else {
      purchaseBtn.textContent = 'Купить Telegram Stars';
    }
  });

  // Обработчик на кнопку покупки
  purchaseBtn.addEventListener('click', handlePurchase);
}

async function handlePurchase() {
  const starsInput = document.getElementById('starsAmount');
  const recipientInput = document.getElementById('recipient');
  const starsValue = parseInt(starsInput.value.trim(), 10);

  // Валидация количества
  if (isNaN(starsValue) || starsValue < 50 || starsValue > 1000000) {
    alert('Введите корректное количество Telegram Stars (от 50 до 1 000 000).');
    return;
  }

  // Валидация юзернейма
  const username = recipientInput.value.trim();
  if (!username) {
    alert('Поле Telegram юзернейма не должно быть пустым.');
    return;
  }
  const usernameRegex = /^[a-zA-Z][a-zA-Z0-9_]{4,31}$/;
  if (!usernameRegex.test(username)) {
    alert('Введите корректный Telegram юзернейм (от 5 до 32 символов, начинается с буквы).');
    return;
  }

  // Создаём счёт на оплату
  const {
    url,
  } = await createInvoice(starsValue, username)

  // Получаем данные пользователя через серверное API
  fetchUserData(username)
    .then(userData => {
      // Берём заголовок без изменений
      const headerHTML = document.querySelector('.purchase-header').outerHTML;
      // Формируем новый HTML-блок без смещения заголовка
      // Формируем новый HTML-блок
const newContent = `
<div class="header-wrapper">
  <div class="header-left">
    <button class="back-icon-btn"><img src="images/back.png" alt="Назад"></button>
  </div>
  <div class="header-center">
    ${headerHTML}
  </div>
  <div class="header-right"></div>
</div>
<div class="user-block">
  <div class="user-info">
    <img src="${userData.avatar}" alt="User Avatar" class="user-avatar">
    <p class="user-text">
      <span class="highlight-stars">${starsValue} ЗВЕЗД</span> ДЛЯ <span class="highlight-name">${userData.name}</span>
    </p>
  </div>
  <a href="${url}" class="payment-btn" target="_blank">
    <span>Рублями (Lava)</span>
    <img src="images/payment.png" alt="Icon" class="payment-icon">
  </a>
  <p class="status">Статус: Не оплачено</p>
  <a href="https://t.me/StarSell_support" class="support-link">Написать в поддержку</a>
</div>
`;
document.querySelector('.purchase-section').innerHTML = newContent;

      document.querySelector('.purchase-section').innerHTML = newContent;

      // Обработчик кнопки "Назад"
      const backIconBtn = document.querySelector('.back-icon-btn');
      backIconBtn.addEventListener('click', () => {
        // Восстанавливаем исходную форму
        document.querySelector('.purchase-section').innerHTML = originalFormHTML;
        // Повторно инициализируем обработчики
        initForm();
      });
    })
    .catch(error => {
      alert('Ошибка получения данных пользователя: ' + error);
    });
}

function createInvoice(
  starsQuantity,
  username,
)
{
  return fetch(`https://starsellpro.com/create-invoice.php?stars_quantity=${starsQuantity}&nickname=@${username}`)
  .then(res => res.json())
  .then(data => {
    return data
  })
}

function fetchUserData(username) {
  return fetch(`https://starsellpro.com/get-username.php?username=${username}`)
    .then(res => res.json())
    .then(data => {
      if (data.error) {
        throw data.error;
      }
      return { avatar: data.photo, name: data.name };
    });
}

// Инициализация формы при загрузке страницы
initForm();
