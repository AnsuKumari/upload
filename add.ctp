<?php
/**
  * @var \App\View\AppView $this
  */
?>


    <!--==================
        Video
    ===================-->
    <section class="counter-cta counter-cta08 cta12">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                </div>
                <div class="col-md-6 col-sm-6">
                    <div class="cta-from">
                        <?= $this->Form->create($document, ['type' => 'file']) ?>
                            <h3>Add Document</h3>
                            <div class="form-group">
                                <?php
                                    echo $this->Form->input('category', array('options' => array('calendar' => 'Calendar', 'exam' => 'Exam', 'results' => 'Results', 'syllabus' => 'Syllabus', 'time table' => 'Time Table', 'college info' => 'College Info', 'events' => 'Events', 'tenders' => 'Tenders', 'job' => 'Job Opportunities', 'notices' => 'Notices', 'placement' => 'Placement', 'rti' => 'RTI'), 'label' => false));
                                    echo $this->Form->input('file', ['type' => 'file', 'label' => false]);
                                    echo $this->Form->control('title', array('placeholder' => 'Title', 'label' => false));
                                    echo $this->Form->control('description', array('placeholder' => 'Description', 'label' => false));
                                    echo $this->Form->control('year', array('placeholder' => 'Year', 'label' => false));
                                ?>
                                <div>
                                    <button type="submit" class="el-btn-medium">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div><!-- Ends: .cta-form-->
                </div>
            </div>
        </div>
    </section><!-- Ends: .counter-cta --> 

