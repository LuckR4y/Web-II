# Sistema de Gestão de Hotel

Este diretório contém um sistema simples de gestão de hospedagens de um hotel, desenvolvido em **PHP** com banco de dados **MySQL**. O sistema permite cadastrar hóspedes, adicionar consumos, encerrar contas e limpar o histórico de hospedagens.

---

## Estrutura do Projeto

- `hotel.sql`  
  Contém o **script SQL** para criar as tabelas necessárias no banco de dados:
  - `hospedes`  
  - `aposentos`  
  - `contas`  
  - `hospedagens`  
  - `consumos`  

- `index.php` (ou outro nome do arquivo PHP)  
  Contém o **código PHP** responsável por:
  1. Conectar ao banco de dados.
  2. Cadastrar hóspedes em quartos disponíveis.
  3. Adicionar consumos à conta do hóspede.
  4. Encerrar a conta e liberar o quarto.
  5. Limpar o histórico de hospedagens.
  6. Listar hóspedes, quartos disponíveis e status das contas.

---

## Funcionalidades

1. **Cadastrar Hóspede**:  
   - Seleciona quarto disponível, datas de entrada e saída, e informações pessoais do hóspede.

2. **Adicionar Consumo**:  
   - Permite adicionar consumos (café da manhã, almoço, jantar ou serviço de quarto) à conta do hóspede.

3. **Encerrar Conta**:  
   - Marca a conta como paga e libera o quarto.

4. **Limpar Histórico**:  
   - Remove todos os registros de hóspedes, contas, consumos e libera todos os quartos.

5. **Visualização**:  
   - Mostra todos os hóspedes cadastrados, quartos disponíveis, valores totais e status de pagamento.

---

## Como Usar

1. Execute o script `hotel.sql` no seu banco de dados MySQL para criar todas as tabelas necessárias.  
2. Configure o arquivo `index.php` com os dados da conexão ao MySQL (usuário, senha e banco de dados).  
3. Abra o arquivo `index.php` no navegador para utilizar o sistema.

---

Feito por **Arthur Vital Fontana** - 839832
