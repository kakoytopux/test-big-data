const form = document.querySelector('.form');
const inputName = form.querySelector('.form__name');
const inputContact = form.querySelector('.form__contact');


function getDeviceName() {
  const userAgent = navigator.userAgent.toLowerCase();

  const isMobile = /mobile|iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(userAgent);
  const isTablet = /tablet|ipad/i.test(userAgent);

  if (isMobile) {
    return 'Mobile';
  }

  if (isTablet) {
    return 'Tablet';
  }
  
  return 'Desktop';
}

function getBrowserName() {
  const userAgent = navigator.userAgent;

  if (userAgent.includes('YaBrowser')) {
    return 'Yandex';
  } else if (userAgent.includes('Chrome')) {
    return 'Google Chrome';
  } else if (userAgent.includes('Firefox')) {
    return 'Mozilla Firefox';
  } else if (userAgent.includes('Safari')) {
    return 'Safari';
  } else if (userAgent.includes('Edge')) {
    return 'Microsoft Edge';
  }

  return 'Other';
}

function getPlatformName() {
  const macosPlatforms = ['Macintosh', 'MacIntel', 'MacPPC', 'Mac68K', 'Mac', 'MacOS'];
  const windowsPlatforms = ['Win32', 'Win64', 'Windows', 'WinCE'];
  const linuxPlatforms = ['Linux'];
  const iosPlatforms = ['iPhone', 'iPad', 'iPod'];
  const androidPlatforms = ['Android'];

  if (findPlatformName(macosPlatforms)) {
    return 'MacOS';
  } else if (findPlatformName(windowsPlatforms)) {
    return 'Windows';
  } else if (findPlatformName(linuxPlatforms)) {
    return 'Linux';
  } else if (findPlatformName(iosPlatforms)) {
    return 'iOS';
  } else if (findPlatformName(androidPlatforms)) {
    return 'Android';
  }

  return 'Other';
}

function findPlatformName(platforms) {
  const userAgent = navigator.userAgent;

  return platforms.some(platform => {
    return userAgent.includes(platform);
  });
}

function onSubmitFeedback(evt) {
  evt.preventDefault();

  fetch('http://localhost/test-big-data/api/createFeedback.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      username: inputName.value,
      info: inputContact.value
    })
  })
  .then(res => res.ok ? res.json() : Promise.reject(res))
  .then(res => console.log(res)) 
  .catch(err => console.error(err));
}

function createVisit(res) {
  if (res.some(item => item.domain === window.location.host)) {
    document.cookie = 'visiting=true';

    fetch('http://localhost/test-big-data/api/createVisit.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        domain: window.location.host,
        page: window.location.href,
        user_agent: navigator.userAgent,
        browser: getBrowserName(),
        device: getDeviceName(),
        platform: getPlatformName()
      })
    })
    .then(res => res.ok ? res.json() : Promise.reject(res))
    .then(res => console.log(res)) 
    .catch(err => console.error(err));
  }
}

if (!document.cookie.includes('visiting')) {
  fetch('http://localhost/test-big-data/api/getDomains.php', {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json'
    }
  })
  .then(res => res.ok ? res.json() : Promise.reject(res))
  .then(res => {
    createVisit(res);
  })
  .catch(err => console.error(err));
}


form.addEventListener('submit', onSubmitFeedback);