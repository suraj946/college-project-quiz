let questions = [];
const questionsBody = document.getElementById('questionsBody');
const questionInput = document.getElementById('question');
const answerInput = document.getElementById('answer');
const option1Input = document.getElementById('option1');
const option2Input = document.getElementById('option2');
const option3Input = document.getElementById('option3');
const option4Input = document.getElementById('option4');
const difficultyInput = document.getElementById('difficulty');

const questionError = document.getElementById('questionError');
const answerError = document.getElementById('answerError');
const option1Error = document.getElementById('option1Error');
const option2Error = document.getElementById('option2Error');
const option3Error = document.getElementById('option3Error');
const option4Error = document.getElementById('option4Error');

const modalBtn = document.getElementById('modalBtn');
const modalHeader = document.getElementById('modal-header');
const saveBtn = document.getElementById('saveBtn');
const cancelBtn = document.getElementById('cancelBtn');

const fileBtn = document.getElementById('fileBtn');
const fileInput = document.getElementById('fileInput');
const spinner = document.getElementById('spinner');

const validateInputs = (input, errorTag) => {
  if (input.value.trim() === '') {
    errorTag.textContent = 'This field is required';
    return false;
  }
  errorTag.textContent = '';
  return true;
};

const inputWithError = [
  [questionInput, questionError],
  [answerInput, answerError],
  [option1Input, option1Error],
  [option2Input, option2Error],
  [option3Input, option3Error],
  [option4Input, option4Error],
];

inputWithError.forEach((inputError) => {
  inputError[0].addEventListener('input', () => {
    validateInputs(inputError[0], inputError[1]);
  });
});

document.addEventListener('DOMContentLoaded', fetchQuestions);
async function fetchQuestions() {
  const response = await fetch('/api/questions');
  const data = await response.json();
  questions = data.questions;
  renderQuestions();
}

document.getElementById('saveBtn').addEventListener('click', async function () {
  const errors = inputWithError.map((inputError) =>
    validateInputs(inputError[0], inputError[1]),
  );
  if (errors.includes(false)) return;
  const ifExists = this.getAttribute('data-qId');
  if (ifExists === 'null' || ifExists === null) await addQuestion();
  else await updateQuestion(Number(ifExists));
});

async function addQuestion() {
  const ops = [
    option1Input.value,
    option2Input.value,
    option3Input.value,
    option4Input.value,
  ];
  if (!validateAnswerOptions(answerInput.value, ops)) {
    showPopup('A option must be same as the answer');
    return;
  }
  let optionsStr = '';
  ops.forEach((op) => {
    optionsStr += `${op.trim().toLowerCase()}$`;
  });
  const data = {
    question: questionInput.value,
    answer: answerInput.value.trim().toLowerCase(),
    options: optionsStr,
    difficulty: difficultyInput.value,
  };
  const response = await fetch('/api/questions', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(data),
  });
  const result = await response.json();
  showPopup(result.message);
  questions.push(result.question);
  addQuestionOnDOM(result.question);
  resetForm();
}
async function updateQuestion(id) {
  const ops = [
    option1Input.value,
    option2Input.value,
    option3Input.value,
    option4Input.value,
  ];
  if (!validateAnswerOptions(answerInput.value, ops)) {
    showPopup('A option must be same as the answer');
    return;
  }
  let optionsStr = '';
  ops.forEach((op) => {
    optionsStr += `${op.trim().toLowerCase()}$`;
  });
  const data = {
    id,
    question: questionInput.value,
    answer: answerInput.value.trim().toLowerCase(),
    options: optionsStr,
    difficulty: difficultyInput.value,
  };
  const response = await fetch('/api/questions', {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(data),
  });
  const result = await response.json();
  showPopup(result.message);
  questions = questions.map((question) => {
    if (question.id == id) {
      return result.question;
    }
    return question;
  });
  renderQuestions();
  cancelBtn.click();
}

async function deleteQuestion(id) {
  const response = await fetch(`/api/questions`, {
    method: 'DELETE',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({ id }),
  });
  const result = await response.json();
  console.log(result);

  showPopup(result.message);
  questions = questions.filter((question) => question.id != id);
  renderQuestions();
  cancelBtn.click();
}

const addQuestionOnDOM = (question) => {
  const row = document.createElement('tr');
  row.innerHTML = `
      <td>${question.id}</td>
      <td>${question.question}</td>
      <td>${question.answer}</td>
      <td>${question.options.replace(/\$/g, ',  ')}</td>
      <td>${question.difficulty}</td>
      <td>
          <button class="btn btn-warning mybtn" onclick="openModalOnEdit(${question.id})">Edit</button>
          <button class="btn btn-danger mybtn" onclick="deleteQuestion(${question.id})">Delete</button>
      </td>
  `;
  questionsBody.appendChild(row);
};

const renderQuestions = () => {
  questionsBody.innerHTML = '';
  questions.forEach(addQuestionOnDOM);
};

const openModalOnEdit = (qId) => {
  const question = questions.find((question) => question.id == qId);
  if (question) {
    questionInput.value = question.question;
    answerInput.value = question.answer;
    const options = question.options.split('$');
    option1Input.value = options[0];
    option2Input.value = options[1];
    option3Input.value = options[2];
    option4Input.value = options[3];
    difficultyInput.value = question.difficulty;
    modalHeader.textContent = 'Edit Question';
    saveBtn.textContent = 'Update';
    saveBtn.setAttribute('data-qId', qId);
    modalBtn.click();
  }
};

const resetModalOnAdd = () => {
  resetForm();
  modalHeader.textContent = 'Add Question';
  saveBtn.textContent = 'Add Question';
  saveBtn.setAttribute('data-qId', null);
};

const resetForm = () => {
  questionInput.value = '';
  answerInput.value = '';
  option1Input.value = '';
  option2Input.value = '';
  option3Input.value = '';
  option4Input.value = '';
  difficultyInput.value = 'easy';
};

const resetErrors = () => {
  questionError.textContent = '';
  answerError.textContent = '';
  option1Error.textContent = '';
  option2Error.textContent = '';
  option3Error.textContent = '';
  option4Error.textContent = '';
};

cancelBtn.addEventListener('click', () => {
  resetModalOnAdd();
  resetErrors();
});

const validateAnswerOptions = (ans, options) => {
  const answer = ans.trim().toLowerCase();
  const optionsArr = options.map((op) => op.trim().toLowerCase());
  return optionsArr.includes(answer);
};

function validateJson(data) {
  if (!Array.isArray(data)) {
    showPopup('Invalid JSON format');
    return false;
  }
  const optionRegex =
    /^[a-z0-9\s@#%^&*()_+=[\]{}|;:'",.<>\/?!-]+\$[a-z0-9\s@#%^&*()_+=[\]{}|;:'",.<>\/?!-]+\$[a-z0-9\s@#%^&*()_+=[\]{}|;:'",.<>\/?!-]+\$[a-z0-9\s@#%^&*()_+=[\]{}|;:'",.<>\/?!-]+$/;

  for (let i = 0; i < data.length; i++) {
    const { question, answer, options, difficulty } = data[i];
    if (!question || !answer || !options || !difficulty) {
      showPopup(`Invalid question at index ${i}`);
      return false;
    }

    if (!optionRegex.test(options)) {
      showPopup(`Invalid options at index ${i}`);
      return false;
    }

    const optionArray = options.split('$').map((opt) => opt.trim());
    if (!optionArray.includes(answer.trim())) {
      showPopup(`Invalid answer at index ${i}`);
      return false;
    }
    if (!['easy', 'medium', 'hard'].includes(difficulty)) {
      showPopup(`Invalid difficulty at index ${i}`);
      return false;
    }
  }
  return true;
}

fileInput.addEventListener('change', (e) => {
  const file = e.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = async function (e) {
      const jsonData = JSON.parse(e.target.result);
      if (!validateJson(jsonData)) return;
      fileBtn.style.display = 'none';
      spinner.classList.remove('hide');
      await uploadToServer(jsonData);
      spinner.classList.add('hide');
      fileBtn.style.display = 'block';
    };
    reader.readAsText(file);
  } else {
    showPopup('Please select a file');
  }
});

const uploadToServer = async (jsonData) => {
  try {
    const response = await fetch('/api/questions/json-upload', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ jsonData }),
    });
    const result = await response.json();
    if (result.success) {
      showPopup(result.message);
      await fetchQuestions();
    } else {
      showPopup(result.message);
    }
  } catch (error) {
    showPopup(error.message);
  }
};
