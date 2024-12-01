let questions = [];
const easyBtn = document.querySelector('#easy');
const mediumBtn = document.querySelector('#medium');
const hardBtn = document.querySelector('#hard');

const difficultyContainer = document.querySelector('#difficulty-container');
const gameCanvas = document.querySelector('#gameCanvas');
const spinner = document.querySelector('#spinner');

const questionTxt = document.querySelector('#questionTxt');
const labels = document.querySelectorAll('.labels');
const radioInput = document.querySelectorAll('.inputRadios');
const btn = document.querySelector('#btn');
const questionBox = document.querySelector('.questionBox');
const summary = document.querySelector('.summary');
const summaryBox = document.querySelector('.summaryBox');
const scoreText = document.querySelector('#score');
const replayBtn = document.getElementById('replayBtn');
const restartBtn = document.getElementById('restartBtn');
let counter = 0;
let allAnswers = [];

easyBtn.addEventListener('click', async() => {
  await getQuestions('easy');
});

mediumBtn.addEventListener('click', async() => {
  await getQuestions('medium');
});

hardBtn.addEventListener('click', async() => {
  await getQuestions('hard');
});

const getQuestions = async(level) => {
  spinner.style.display = 'block';
  const response = await fetch("/api/quiz-questions",
    {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({difficulty: level}),
    }
  );
  const data = await response.json();
  spinner.style.display = 'none';
  if(data.success) {
    questions = data.questions;
    if(questions.length === 0) {
      showPopup("No Questions Available!!");
      return;
    }
    difficultyContainer.style.display = 'none';
    gameCanvas.classList.remove('hide');
    startGame(questions);
    showPopup(data.message);
  }else{
    showPopup(data.message);
  }
}

const startGame = (data) => {
  btn.textContent = `Next Question (${counter + 1}/${questions.length})`;
  questionTxt.textContent = data[counter].question;
  let options = data[counter].options.split('$');
  labels.forEach((item, i) => {
    item.textContent = options[i];
    radioInput[i].setAttribute('value', options[i]);
    radioInput[i].checked = false;
    radioInput[i].setAttribute('qId', data[counter].id);
  });
  counter++;
  if(counter === questions.length) {
    btn.textContent = `Show Results (${counter}/${questions.length})`;
  }
};

btn.addEventListener('click', () => {
  let checkedValue = document.querySelector(
    "input[type='radio'][name='option']:checked",
  );
  if (!checkedValue) {
    showPopup('Please select an option');
    return;
  }
  let qId = checkedValue.getAttribute('qId');
  allAnswers.push({
    userAns: checkedValue.value,
    rightAns: questions.find(({ id }) => id == qId).answer,
    question: questions.find(({ id }) => id == qId).question,
  });
  console.log({counter, leg: questions.length});
  
  if (counter === questions.length - 1) {
    btn.textContent = `Show Results (${counter + 1}/${questions.length})`;
  }else{
    btn.textContent = `Next Question (${counter + 1}/${questions.length})`;
  }
  if (counter < questions.length) {
    startGame(questions);
  } else {
    showSummary(allAnswers);
  }
});

const showSummary = (answers) => {
  let score = 0;
  questionBox.style.display = 'none';
  answers.forEach((item, i) => {
    let element = document.createElement('div');
    let str = `<p class="ques">${item.question}</p>
            <p class="ans">Your : ${item.userAns}</p>
            <p class="ans">Right : ${item.rightAns}</p>`;
    element.classList.add('result');
    element.innerHTML = str;
    if (item.userAns == item.rightAns) {
      score++;
      element.classList.add('right');
    } else {
      element.classList.add('wrong');
    }
    summary.append(element);
  });
  summaryBox.style.display = 'initial';
  scoreText.textContent = `Your score is ${score} out of ${answers.length}`;
};

replayBtn.addEventListener('click', () => {
  allAnswers = [];
  counter = 0;
  summary.innerHTML = '';
  summaryBox.style.display = 'none';
  questionBox.style.display = 'flex';
  btn.textContent = `Next Question (1/${questions.length})`;
  startGame(questions);
});

restartBtn.addEventListener('click', () => {
  location.reload();
});

const playGame = () => {
  startGame(questions);
};
