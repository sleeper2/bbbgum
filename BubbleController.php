<?php

class BubbleController extends Controller
{
    public function filters()
    {
        return array(
            array(
                'application.filters.CheckAnyToken + list, view',
            ),
            array(
                'application.filters.CheckEditToken + create, update, delete',
            ),
        );
    }

    public function actionList($frameId = null)
    {
        $bubbles = Bubble::model()
            ->iCanView()
            ->withFrameId($frameId)
            ->with(Rest::with(Bubble::model()))
            ->findAll();

        $this->jsonRender($bubbles);
    }

    public function actionView($id, $frameId = null)
    {
        $bubble = Bubble::model()
            ->iCanView()
            ->withFrameId($frameId)
            ->withId($id)
            ->with(Rest::with(Bubble::model()))
            ->find();

        if (!$bubble) {
            throw new ApiException(404, 'Bubble not found.');
        }
        $this->jsonRender($bubble);
    }

    public function actionCreate($frameId = null)
    {
        $data = Yii::app()->request->restParams;
        if ($frameId) {
            $data['frame_id'] = $frameId;
        }

        $bubble = new Bubble;
        $bubble->attributes = $data;
        if (!$bubble->save()) {
            throw new ApiException(422, 'Cant save bubble.', $bubble->errors);
        }
        $this->jsonRender($bubble, 201);
    }

    public function actionUpdate($id, $frameId = null)
    {
        $bubble = Bubble::model()
            ->iCanEdit()
            ->withFrameId($frameId)
            ->withId($id)
            ->with(Rest::with(Bubble::model()))
            ->find();

        if (!$bubble) {
            throw new ApiException(404, 'Bubble not found.');
        }
        $bubble->attributes = Yii::app()->request->restParams;
        $bubble->save();
        $this->jsonRender($bubble);
    }

    public function actionDelete($id, $frameId = null)
    {
        $bubble = Bubble::model()
            ->iCanEdit()
            ->withFrameId($frameId)
            ->withId($id)
            ->with(Rest::with(Bubble::model()))
            ->find();

        if (!$bubble) {
            throw new ApiException(404, 'Bubble not found.');
        }
        if (!$bubble->delete()) {
            throw new ApiException(422, 'Unable to delete bubble.');
        }
        $this->jsonRender($bubble);
    }
}
