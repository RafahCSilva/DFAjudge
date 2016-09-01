# Repositório público do DFAjudge

![DFAjuge logo](logos/LOGOhome.png?raw=true)

**DFAjudge** - Sistema de auxílio na aprendizagem da disciplina de Linguagens Formais e Autômatos

Desenvolvido por **Rafael Cardoso da Silva**

## Descrição
Um sistema para correção automatica de exercícios envolvendo Autômatos Finitos Determinísticos, utilizando o algoritmo de Teste de Equivalência de Hopcroft e Karp.

Linguagens/Tecnologias utilizadas: PHP, MySQL, HTML5, JS, SCSS.

E para a confecção do autômato-resposta foi utilizado este outro projeto: [**DFAdesigner**](https://github.com/RafahCSilva/DFAdesigner) ( e você pode testa-lo
[aqui](https://rafahcsilva.github.io/DFAdesigner) )

## Resumo
Resolver exercícios é fundamental para um aluno fixar os conceitos apresentados em aula. Por outro lado, ter seus exercícios corrigidos também é muito importante, para que ele possa avaliar o seu aprendizado. Na UFABC, a disciplina de Linguagens Formais e Autômatos contempla vários exercícios que admitem infinitas respostas, o que torna a correção deles praticamente impossível, principalmente quando as turmas são grandes. O objetivo deste projeto foi a criação e implementação de um sistema para aplicação e correção automática de exercícios envolvendo autômatos finitos determinísticos. Através do estudo de métodos e algoritmos presentes na literatura, foi possível implementar o teste de equivalência entre o autômato-resposta do aluno e o autômato-gabarito previamente armazenado no banco de dados. Ao final do projeto, o sistema foi usado em caráter experimental numa turma da UFABC da disciplina de Linguagens Formais e Autômatos, afim de testar a sua qualidade. E ao final da disciplina, a nota que os alunos obtiverem ao revolver os exercícios do sistema ajudarão a compor o conceito final de cada um na disciplina.

**Palavras-chave:** autômato finito, equivalência de autômatos, minimização de autômato, programação para web


## Instalação
Para instalar:

1. Clone este repositório para seu servidor;
2. Rode o script `_dev/DB.sql` em seu banco de dados;
3. Edite o script `private/DBconfig.php` para configurar sua conexão com o banco de dados e uma conta do Gmail;

## Relatório deste projeto
Para mais detalhes, veja o relatório deste projeto disponível em [`_dev/ICrelFinal_RafaelCardoso.pdf`](_dev/ICrelFinal_RafaelCardoso.pdf)

## Referências
HOPCROFT, J. An n log n algorithm for minimizing states in a finite automaton. In:
Theory of machines and computations (Proc. Internat. Sympos., Technion, Haifa, 1971). Academic Press, New York, 1971. p. 189–196.

HOPCROFT, J.; KARP, R. A Linear Algorithm for Testing Equivalence of Finite
Automata. Cornell University, 1971.


