let originalFormHTML = document.querySelector('.purchase-section').innerHTML;

function initForm() {
  const starsInput = document.getElementById('starsAmount');
  const purchaseBtn = document.querySelector('.purchase-btn');
  const recipientInput = document.getElementById('recipient');

  starsInput.addEventListener('input', () => {
    const value = starsInput.value.trim();
    if (value && !isNaN(value)) {
      purchaseBtn.textContent = `Купить ${value} Telegram Stars`;
    } else {
      purchaseBtn.textContent = 'Купить Telegram Stars';
    }
  });

  purchaseBtn.addEventListener('click', handlePurchase);
}

function handlePurchase() {
  const starsInput = document.getElementById('starsAmount');
  const recipientInput = document.getElementById('recipient');
  const starsValue = parseInt(starsInput.value.trim(), 10);

  if (isNaN(starsValue) || starsValue < 50 || starsValue > 1000000) {
    alert('Введите корректное количество Telegram Stars (от 50 до 1 000 000).');
    return;
  }

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

  fetchUserData(username)
    .then(userData => {
      const headerHTML = document.querySelector('.purchase-header').outerHTML;
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
  <button class="payment-btn">
    <span>Рублями (Lava)</span>
    <img src="images/payment.png" alt="Icon" class="payment-icon">
  </button>
  <p class="status">Статус: Нет</p>
  <a href="https://t.me/StarSell_support" class="support-link">Написать в поддержку</a>
</div>
`;
      document.querySelector('.purchase-section').innerHTML = newContent;

      const backIconBtn = document.querySelector('.back-icon-btn');
      backIconBtn.addEventListener('click', () => {
        document.querySelector('.purchase-section').innerHTML = originalFormHTML;
        initForm();
      });
      
      const paymentBtn = document.querySelector('.payment-btn');
      paymentBtn.addEventListener('click', () => {
        startPayment({
          telegramNick: username,
          starsCount: starsValue,
          name: userData.name
        });
      });
    })
    .catch(error => {
      alert('Ошибка получения данных пользователя: ' + error);
    });
}

function fetchUserData(username) {
  return fetch(`/getUserData?username=${username}`)
    .then(res => res.json())
    .then(data => {
      if (data.error) throw data.error;
      return { avatar: data.photo, name: data.name };
    });
}

function startPayment(orderData) {
  fetch('/create-order', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(orderData)
  })
    .then(res => res.json())
    .then(data => {
      if (data.redirectUrl) {
        window.location.href = data.redirectUrl;
      } else {
        alert('Ошибка создания заказа.');
      }
    })
    .catch(err => {
      alert('Ошибка создания заказа: ' + err);
    });
}

initForm();
