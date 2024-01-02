document.addEventListener("DOMContentLoaded", function () {
  const sidebar = document.getElementById("sidebar");
  const legendas = document.querySelectorAll(".lbl-menu");

  sidebar.addEventListener("mouseenter", function () {
    this.classList.add("expanded");

    legendas.forEach((legenda) => {
      legenda.classList.remove("hide");
    });
  });

  sidebar.addEventListener("mouseleave", function () {
    this.classList.remove("expanded");

    legendas.forEach((legenda) => {
      legenda.classList.add("hide");
    });
  });


  //Masks
  $(document).ready(function () {
    $('#cpf').mask('000.000.000-00', {
      reverse: true
    });
    $('#tel').mask('(00) 00000-0000');

  });
});


function validarCampoObrigatorio(campo) {
  campo.removeClass('required');
  campo.on('input', function () {
    if ($(this).val().trim() !== "") {
      $(this).removeClass('required');
    }
  });
}

function validarFormulario() {
  var nome = $('input[name="nome"]');
  var telefone = $('input[name="tel"]');
  var email = $('input[name="email"]');

  [nome, telefone, email].forEach(validarCampoObrigatorio);

  var camposVazios = [nome, telefone, email].filter(function (campo) {
    return campo.val().trim() === "";
  });

  if (camposVazios.length > 0) {
    exibirModal(false, false);

    camposVazios.forEach(function (campo) {
      campo.addClass('required');
    });

    return false;
  }

  enviarFormulario();
  exibirModal(true, true);

  return false;
}

$('input[name="nome"], input[name="tel"], input[name="email"]').each(function () {
  validarCampoObrigatorio($(this));
});

function exibirModal(sucesso) {
  var title = sucesso ? "Oba! Contato salvo!" : "Ops! Algo deu errado!";
  var text = sucesso ? "O contato já foi adicionado em sua agenda" : "Preencha os campos obrigatórios para continuar";
  var icon = sucesso ? "success" : "error";

  Swal.fire({
    title: title,
    text: text,
    icon: icon,
    confirmButtonColor: '#3085d6',
    confirmButtonText: 'ENTENDI'
  }).then((result) => {
    if (result.isConfirmed && sucesso) {
      window.location.href = 'contatos.php'; 
    }
  });
}


function truncateEmailInCard(card) {
  var emailElement = card.querySelector('.truncate-email .email-content');

  if (emailElement) {
    var originalEmail = emailElement.getAttribute('data-original-email');
 
    if (originalEmail.length > 10) {
      var truncatedEmail = originalEmail.substring(0, 10) + '...';
      emailElement.innerText = truncatedEmail;
    }
  }
}

// Função para tratar o email no card
function truncateEmailInCard(card) {
  var emailElement = card.querySelector('.truncate-email .email-content');

  if (emailElement) {
    var originalEmail = emailElement.innerText;
    emailElement.setAttribute('data-original-email', originalEmail);


    if (originalEmail.length > 10) {
      var truncatedEmail = originalEmail.substring(0, 10) + '...';
      emailElement.innerText = truncatedEmail;
    }
  }
}

function abreContato(card) {
  var modal = document.getElementById('modal');
  var modalContent = modal.querySelector('.card-contato-modal');

  var cardClone = card.closest('.card-contato').cloneNode(true);
  modalContent.innerHTML = '';
  modalContent.appendChild(cardClone);

  var emailElementModal = modalContent.querySelector('.truncate-email .email-content');
  if (emailElementModal) {
    emailElementModal.innerText = emailElementModal.getAttribute('data-original-email');
  }

  modal.style.display = 'block';
}

function fechaModal() {
  var modal = document.getElementById('modal');
  modal.style.display = 'none';
}

document.addEventListener('DOMContentLoaded', function () {
  var truncateElements = document.querySelectorAll('.truncate-email');
  truncateElements.forEach(function (element) {
    truncateEmailInCard(element);
  });
});

function excluirContato(button) {
  Swal.fire({
    title: 'Excluir contato',
    text: 'Você realmente deseja excluir esse contato?',
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#04033B',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Sim',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (result.isConfirmed) {
      Swal.fire({
        title: 'Processando ...',
        text: 'As informações do contato serão deletadas em breve de sua conta',
        icon: 'info',
        confirmButtonColor: '#04033B',
        confirmButtonText: 'ENTENDI',
      })

      modal.style.display = 'none';
    }
  });
}