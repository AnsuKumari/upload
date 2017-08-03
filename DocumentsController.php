<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Controller\SubscribersController;

//<?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $document->id], ['confirm' => __('Are you sure you want to delete # {0}?', $document->id)]) 

/**
 * Documents Controller
 *
 * @property \App\Model\Table\DocumentsTable $Documents
 *
 * @method \App\Model\Entity\Document[] paginate($object = null, array $settings = [])
 */
class DocumentsController extends AppController
{

	public $paginate = [
        'limit' => 5,
    ];


    public function initialize() {
        parent::initialize();
        $this->loadComponent('Paginator');
        $this->Auth->allow(['download', 'find', 'search']);
    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $documents = $this->paginate($this->Documents);

        $this->set(compact('documents'));
        $this->set('_serialize', ['documents']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $Subscribers = new SubscribersController;
        $document = $this->Documents->newEntity();
        if ($this->request->is('post')) {
            $document = $this->Documents->patchEntity($document, $this->request->getData());
            $name = $this->request->data['file']['name'];
            $category = $this->request->data['category'];
            if(!empty($name)){
                if(move_uploaded_file($this->request->data['file']['tmp_name'], 'files/'.$category.'/'.$name)) {
                    $document->name = $name;
                    $document->path = 'files/'.$category.'/'.$name;
                    $document->user_id = $this->Auth->user('id');
                    if ($this->Documents->save($document)) {
                        $this->Flash->success(__('The document has been saved.'));

                        $controller = (preg_match("/calendar|exam|results|syllabus|time table/i", $category) ? 'academics' : 'notices');

                        $Subscribers->send($controller, $category, $document->description);
                        return $this->redirect(['controller' => $controller, 'action' => $category]);
                    }
                }
            }
            
            $this->Flash->error(__('The document could not be saved. Please, try again.'));
        }
        $this->set(compact('document'));
        $this->set('_serialize', ['document']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Document id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $document = $this->Documents->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $document = $this->Documents->patchEntity($document, $this->request->getData());
            $name = $this->request->data['file']['name'];
            if(!empty($name)){
                if(move_uploaded_file($this->request->data['file']['tmp_name'], 'files/'.$document['category'].'/'.$name)) {
                    $document->name = $name;
                }
            }
            if ($this->Documents->save($document)) {
                $this->Flash->success(__('The document has been saved.'));

                return $this->redirect(['controller' => 'academics', 'action' => $document['category']]);
            }
            $this->Flash->error(__('The document could not be saved. Please, try again.'));
        }
        $this->set(compact('document'));
        $this->set('_serialize', ['document']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Document id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $document = $this->Documents->get($id);
        if ($this->Documents->delete($document)) {
            $this->Flash->success(__('The document has been deleted.'));
        } else {
            $this->Flash->error(__('The document could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function download($id){
        $file = $this->Documents->get($id);
        $file_path = $file['path'];
        $this->response->file($file_path, array(
            'download' => true,
            'name' => $file['name'],
        ));
        return $this->response;
    }

    public function find(){
        if ($this->request->is('post'))
            $keyword = $this->request->data['keyword'];
        return $this->redirect(['action' => 'search', $keyword]);
    }

    public function search($keyword = null){
    	if(!empty($keyword)){
        	$docs = $this->Documents->find('all', ['conditions' => ['Documents.description LIKE' => '%'. $keyword . '%']]);
        	$this->set('docs', $this->paginate($docs));
        }

        else{
            $this->Flash->error(__('Please write something to search.'));
            $this->redirect($this->referer());
        }
    }
}
